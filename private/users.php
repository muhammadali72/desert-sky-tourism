<?php
function accountProfile(string $email):array{return getRecord('user_profiles',hash('sha256',strtolower($email)))??[];}
function accountDisabled(string $email):bool{return !empty(accountProfile($email)['disabled']);}
function accountLoginRecorded(string $email):void{$p=accountProfile($email);$p['lastLogin']=gmdate('c');putRecord('user_profiles',hash('sha256',strtolower($email)),$p);}
function managedUsers():array{customerSchema();$users=[];$bookings=records('bookings',true);foreach(sql('SELECT id,email,name,verified,created FROM customers')->fetchAll(PDO::FETCH_ASSOC) as $c)$users[$c['email']]=['email'=>$c['email'],'name'=>$c['name'],'customerId'=>$c['id'],'verified'=>(bool)$c['verified'],'createdAt'=>$c['created'],'role'=>'subscriber'];foreach(sql('SELECT email FROM accounts')->fetchAll(PDO::FETCH_COLUMN) as $email){$users[$email]=($users[$email]??['email'=>$email,'name'=>'','customerId'=>'','verified'=>false,'createdAt'=>''])+[];$users[$email]['role']='administrator';}foreach($users as $email=>&$u){$p=accountProfile($email);$u['name']=$p['name']??$u['name'];$u['phone']=$p['phone']??'';$u['createdAt']=$p['createdAt']??$u['createdAt'];$u['lastLogin']=$p['lastLogin']??'';$u['disabled']=!empty($p['disabled']);$u['bookingCount']=0;foreach($bookings as $b)if($u['customerId']&&($b['customerId']??'')===$u['customerId']&&empty($b['deletedAt']))$u['bookingCount']++;}unset($u);$users=array_values($users);usort($users,fn($a,$b)=>strcmp($a['email'],$b['email']));return $users;}
function saveManagedUser(array $v,string $actor):array{
 customerSchema();$email=strtolower(trim((string)($v['email']??'')));$name=text($v['name']??'',150);$phone=text($v['phone']??'',50);$role=$v['role']??'';$disabled=!empty($v['disabled']);$password=(string)($v['password']??'');$create=($v['action']??'')==='create';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>254||mb_strlen($name)<2||!in_array($role,['administrator','subscriber']))throw new InvalidArgumentException('Enter a name, valid email and supported role.');
 if(($create||$password!=='')&&(strlen($password)<12||strlen($password)>200))throw new InvalidArgumentException('Use a password of 12–200 characters.');
 if($email===$actor&&($disabled||$role!=='administrator'||$password!==''))throw new InvalidArgumentException('Keep your own administrator access active. Use Account security to change your own password.');
 $db=database();$db->exec('BEGIN IMMEDIATE');try{
  $a=sql('SELECT * FROM accounts WHERE email=?',[$email])->fetch(PDO::FETCH_ASSOC);$c=sql('SELECT * FROM customers WHERE email=?',[$email])->fetch(PDO::FETCH_ASSOC);$p=accountProfile($email);
  if($create&&($a||$c))throw new InvalidArgumentException('This email already has an account. Edit that user instead.');if(!$create&&!$a&&!$c)throw new InvalidArgumentException('User not found.');
  if($a&&($disabled||$role!=='administrator')){$active=0;foreach(sql('SELECT email FROM accounts')->fetchAll(PDO::FETCH_COLUMN) as $e)if(!accountDisabled($e))$active++;if(!accountDisabled($email)&&$active<=1)throw new InvalidArgumentException('At least one active administrator must remain.');}
  $hash=$password!==''?password_hash($password,PASSWORD_DEFAULT):($a['hash']??$c['hash']??'');$securityChange=$password!==''||$disabled!==!empty($p['disabled'])||($a?'administrator':'subscriber')!==$role;
  if($role==='administrator'){if(!$a)sql('INSERT INTO accounts(email,hash,version) VALUES(?,?,?)',[$email,$hash,(int)($p['adminVersion']??0)+1]);elseif($securityChange)sql('UPDATE accounts SET hash=?,version=version+1 WHERE email=?',[$hash,$email]);}
  else{if(!$c){$id=bin2hex(random_bytes(16));sql('INSERT INTO customers(id,email,name,hash,version,verified,created) VALUES(?,?,?,?,1,0,?)',[$id,$email,$name,$hash,gmdate('c')]);}if($a)sql('DELETE FROM accounts WHERE email=?',[$email]);}
  if($c){if($securityChange)sql('UPDATE customers SET name=?,hash=?,version=version+1 WHERE id=?',[$name,$password!==''?$hash:$c['hash'],$c['id']]);else sql('UPDATE customers SET name=? WHERE id=?',[$name,$c['id']]);if($securityChange)sql('DELETE FROM customer_tokens WHERE customer=?',[$c['id']]);}
  $p=array_merge($p,['name'=>$name,'phone'=>$phone,'disabled'=>$disabled,'createdAt'=>$p['createdAt']??$c['created']??($create?gmdate('c'):''),'adminVersion'=>max((int)($p['adminVersion']??0),(int)($a['version']??0))+($securityChange?1:0),'updatedAt'=>gmdate('c')]);putRecord('user_profiles',hash('sha256',$email),$p);$db->exec('COMMIT');
 }catch(Throwable $e){$db->exec('ROLLBACK');throw $e;}
 foreach(managedUsers() as $u)if($u['email']===$email)return $u;throw new RuntimeException('Unable to load saved user.');
}
function deleteManagedUser(string $email,string $actor,string $confirmation):void{
 $email=strtolower(trim($email));if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strtolower(trim($confirmation))!==$email)throw new InvalidArgumentException('Confirm the email address of the user to delete.');
 if($email===strtolower($actor))throw new InvalidArgumentException('You cannot delete your own administrator account.');
 customerSchema();$db=database();$db->exec('BEGIN IMMEDIATE');try{
  $a=sql('SELECT version FROM accounts WHERE email=?',[$email])->fetch(PDO::FETCH_ASSOC);$c=sql('SELECT id FROM customers WHERE email=?',[$email])->fetchColumn();$p=accountProfile($email);
  if(!$a&&!$c)throw new InvalidArgumentException('User not found.');
  if($a&&!accountDisabled($email)){$active=0;foreach(sql('SELECT email FROM accounts')->fetchAll(PDO::FETCH_COLUMN) as $e)if(!accountDisabled($e))$active++;if($active<=1)throw new InvalidArgumentException('At least one active administrator must remain.');}
  if($c){sql('DELETE FROM customer_tokens WHERE customer=?',[$c]);sql('DELETE FROM customers WHERE id=?',[$c]);}
  sql('DELETE FROM accounts WHERE email=?',[$email]);
  // Retain only a version counter so reusing this email cannot revive an old admin session.
  putRecord('user_profiles',hash('sha256',$email),['adminVersion'=>max((int)($p['adminVersion']??0),(int)($a['version']??0))+1]);
  $db->exec('COMMIT');
 }catch(Throwable $e){$db->exec('ROLLBACK');throw $e;}
}
