<?php
function normalizeTracking(array $v):array{
 $c=['gtmId'=>strtoupper(trim((string)($v['gtmId']??''))),'gtmEnabled'=>filter_var($v['gtmEnabled']??false,FILTER_VALIDATE_BOOLEAN),'googleVerification'=>trim((string)($v['googleVerification']??'')),'metaPixelId'=>trim((string)($v['metaPixelId']??'')),'metaEnabled'=>filter_var($v['metaEnabled']??false,FILTER_VALIDATE_BOOLEAN)];
 if($c['gtmId']!==''&&!preg_match('/\AGTM-[A-Z0-9]{4,20}\z/',$c['gtmId']))throw new InvalidArgumentException('Enter a Google Tag Manager container ID such as GTM-ABC1234. Paste the ID only.');
 if($c['gtmEnabled']&&$c['gtmId']==='')throw new InvalidArgumentException('Enter a container ID before enabling Google Tag Manager.');
 if($c['googleVerification']!==''&&!preg_match('/\A[A-Za-z0-9_-]{10,256}={0,2}\z/',$c['googleVerification']))throw new InvalidArgumentException('Enter only the content value from your Google verification meta tag.');
 if($c['metaPixelId']!==''&&!preg_match('/\A[0-9]{5,25}\z/',$c['metaPixelId']))throw new InvalidArgumentException('Enter a numeric Meta Pixel ID. Paste the ID only.');
 if($c['metaEnabled']&&$c['metaPixelId']==='')throw new InvalidArgumentException('Enter a Pixel ID before enabling Meta Pixel.');
 return $c;
}
function trackingSettings():array{try{return normalizeTracking(getRecord('settings','tracking')??[]);}catch(InvalidArgumentException){return normalizeTracking([]);}}
function trackingHead(array $c):string{
 $c=normalizeTracking($c);$out='';
 if($c['googleVerification']!=='')$out.='<meta name="google-site-verification" content="'.esc($c['googleVerification']).'">';
 if($c['gtmEnabled']){$id=json_encode($c['gtmId'],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT);$out.="<!-- Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',".$id.");</script><!-- End Google Tag Manager -->";}
 if($c['metaEnabled']){$id=json_encode($c['metaPixelId'],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT);$out.="<!-- Meta Pixel --><script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',".$id.");fbq('track','PageView');</script><!-- End Meta Pixel -->";}
 return $out;
}
function trackingBody(array $c):string{
 $c=normalizeTracking($c);$out='';
 if($c['gtmEnabled'])$out.='<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.esc($c['gtmId']).'" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>';
 if($c['metaEnabled'])$out.='<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id='.esc($c['metaPixelId']).'&amp;ev=PageView&amp;noscript=1"></noscript>';
 return $out;
}
