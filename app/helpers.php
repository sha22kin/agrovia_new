<?php

// Every helper is guarded: tools that probe the app/ folder with class_exists() (e.g. IDE extensions)
// make the PSR-4 autoloader include this file a second time, which must not redeclare anything.

use App\Models\General;
use App\Models\Media;
use App\Models\Country;
use App\Models\Post;
use App\Models\PostExtra;
use App\Models\Attribute;
use Carbon\Carbon;


if (!function_exists('general')) {
// Pages call general() hundreds of times (every title, asset link, price ...), so the settings row is
// read from the database once per request and reused.
function general(){
  static $general =null;
  if($general===null){
    $general =General::first();
  }
  return $general;
}
}

// Intervention Image (v2) manager. Its Image facade can't be used any more: since Laravel 13 the
// container key it resolves ('image') belongs to Laravel's own image service.
if (!function_exists('imageManager')) {
function imageManager(){
  static $manager =null;
  if($manager===null){
    $manager =new \Intervention\Image\ImageManager(['driver'=>'gd']);
  }
  return $manager;
}
}

if (!function_exists('websiteTitle')) {
function websiteTitle($title=null){
  
  $hasTitle =$title?' - ':'';
  $text =general()->title;
  $text1=general()->subtitle;
  $text2=$text&&$text1?' - ':'';
  return $title.$hasTitle.$text.$text2.$text1;
}
}

if (!function_exists('isMobileDevice')) {
function isMobileDevice() {
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i" 
, $_SERVER["HTTP_USER_AGENT"] ?? '');
}
}

if (!function_exists('adminTheme')) {
function adminTheme(){
  $theme=general()->adminTheme.'.';
  if(isMobileDevice()){
    $theme=general()->adminTheme.'.';
  }
  return $theme;
}
}

if (!function_exists('welcomeTheme')) {
function welcomeTheme(){
  $theme=general()->theme.'.';
  if(isMobileDevice()){
    $theme=general()->theme.'.';
  }
  return $theme;
}
}

if (!function_exists('assetLink')) {
function assetLink(){
  return 'public/'.general()->theme;
}
}

if (!function_exists('assetLinkAdmin')) {
function assetLinkAdmin(){
  return 'public/'.general()->adminTheme;
}
}

// Buyer and seller dashboards have their own themes (resources/views/<theme>, public/<theme>).
// Like the admin/front themes the name may come from the generals table (buyerTheme /
// sellerTheme columns); until those exist the defaults below are used.
if (!function_exists('buyerThemeName')) {
function buyerThemeName(){
  return general()->buyerTheme ?: 'buyer';
}
}

if (!function_exists('sellerThemeName')) {
function sellerThemeName(){
  return general()->sellerTheme ?: 'seller';
}
}

if (!function_exists('buyerTheme')) {
function buyerTheme(){
  return buyerThemeName().'.';
}
}

if (!function_exists('sellerTheme')) {
function sellerTheme(){
  return sellerThemeName().'.';
}
}

// The expert dashboard has its own theme as well (resources/views/expert, public/expert).
if (!function_exists('expertThemeName')) {
function expertThemeName(){
  return general()->expertTheme ?: 'expert';
}
}

if (!function_exists('expertTheme')) {
function expertTheme(){
  return expertThemeName().'.';
}
}

if (!function_exists('assetLinkExpert')) {
function assetLinkExpert(){
  return 'public/'.expertThemeName();
}
}

if (!function_exists('assetLinkBuyer')) {
function assetLinkBuyer(){
  return 'public/'.buyerThemeName();
}
}

if (!function_exists('assetLinkSeller')) {
function assetLinkSeller(){
  return 'public/'.sellerThemeName();
}
}

// URL of the dashboard the logged in user belongs to (home page when the user has no role).
if (!function_exists('dashboardUrl')) {
function dashboardUrl(){
  $route =auth()->check()?auth()->user()->dashboardRoute():null;
  return route($route?:'index');
}
}

if (!function_exists('geoData')) {
function geoData($type=null,$parent=null,$id=null){

  $data =Country::orderBy('name')->select(['id','type','parent_id','name']);
  if($type){
    $data =$data->where('type',$type);
  }

  if($parent){
    $data =$data->where('parent_id',$parent);
  }

  if($id){
    $data =$data->find($id);
  }else{
    $data =$data->get();
  }
  
  return $data;
}
}

if (!function_exists('bn2enNumber')) {
function bn2enNumber ($number){
  $search_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
  $replace_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
  $en_number = str_replace($search_array, $replace_array, $number);

  return $en_number;
}
}

if (!function_exists('en2bnNumber')) {
function en2bnNumber ($number){
  $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
  $replace_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
  $en_number = str_replace($search_array, $replace_array, $number);

  return $en_number;
}
}

if (!function_exists('en2bnMonth')) {
function en2bnMonth($month){
  $search_array= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October","November","December","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct","Nov","Dec","01", "02", "03", "04", "05", "06", "07", "08", "09", "10","11","12");
  $replace_array= array("জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগষ্ট", "সেপ্টেম্বর", "অক্টোবর","নভেম্বর","ডিসেম্বর","জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগষ্ট", "সেপ্টেম্বর", "অক্টোবর","নভেম্বর","ডিসেম্বর","জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগষ্ট", "সেপ্টেম্বর", "অক্টোবর","নভেম্বর","ডিসেম্বর");
  $en_number = str_replace($search_array, $replace_array, $month);
  return $en_number;
}
}

if (!function_exists('priceFormat')) {
function priceFormat($amount=0)
{
  $formatAmount ='';

  $formatAmount = number_format($amount,0);

  return $formatAmount;

}
}


if (!function_exists('priceFullFormat')) {
function priceFullFormat($amount=0)
{
  $formatAmount ='';

  $amountFormet = number_format($amount,0);
  
  $formatAmount = general()->currency.' '.$amountFormet;
 
  return $formatAmount;

}
}

if (!function_exists('sendMail')) {
function sendMail($toEmail,$toName,$subject,$datas,$template,$attachments=null){

  try {
    Mail::send($template,compact('datas'), function ($message) use ($toEmail,$toName,$subject,$attachments) {
        $message->from(general()->mail_from_address, general()->mail_from_name);
        $message->to($toEmail,$toName);
        //To bb mail 
        //$message->cc($ccRecipients);
        //To Replay diffrent mail 
        //$message->replyTo('replyto@example.com', 'Reply To Name');
        
        $message->subject($subject);
        
        if($attachments){
            // Attachments
            foreach ($attachments as $attachment) {
                $message->attach($attachment['path'], [
                    'as' => $attachment['name'],
                    'mime' => $attachment['mime'],
                ]);
            }
        }
        
    });
      return true;
  } catch (Exception $ex) {
      // Was silently swallowed before, so a bad SMTP setup (wrong host, blocked port, bad creds - the
      // usual difference between a working local setup and a fresh live one) left zero trace anywhere,
      // and every caller's "could not send" fallback kicked in with no way to tell why. Now it lands in
      // storage/logs/laravel.log.
      \Log::error('sendMail failed: '.$ex->getMessage(), ['to' => $toEmail, 'template' => $template]);
      return false;
  }

}
}

if (!function_exists('sendSMS')) {
function sendSMS($to,$msg){
  $userId = general()->sms_username;
  $pass = general()->sms_password;
  $masking = general()->sms_senderid;
  if(general()->sms_type=='Non Masking'){
  $url =general()->sms_url_nonmasking; 
  return "{$url}?username={$userId}&password={$pass}&number={$to}&message={$msg}";
  }else{
  $url =general()->sms_url_masking;
  return "{$url}?username={$userId}&password={$pass}&number={$to}&message={$msg}&senderid={$masking}";
  }
}
}

if (!function_exists('slider')) {
function slider($location=null){
  return Attribute::latest()->where('type',1)->where('status','active')->where('location',$location)->first();
}
}

if (!function_exists('menu')) {
function menu($location=null){
  return Attribute::latest()->where('type',8)->where('status','active')->where('location',$location)->first();
}
}

if (!function_exists('page')) {
function page($id=null){
  return Post::where('type',0)->find($id);
}
}

if (!function_exists('pageTemplate')) {
function pageTemplate($template=null){
  return Post::where('type',0)->where('template',$template)->first();
}
}

if (!function_exists('uploadFile')) {
function uploadFile($file,$src,$srcType,$fileUse,$author=null,$fileStatus=true){


  if($fileStatus){
      $media = Media::where('src_type',$srcType)->where('use_Of_file',$fileUse)->where('src_id',$src)->first();
  }else{
      $media = null;
  }

  if(!$media){
    $media =new Media();
    }else{
      if(File::exists($media->file_url)){
            File::delete($media->file_url);
        }
        if(File::exists($media->file_url_sm)){
            File::delete($media->file_url_sm);
        }
        if(File::exists($media->file_url_md)){
            File::delete($media->file_url_md);
        }
        if(File::exists($media->file_url_lg)){
            File::delete($media->file_url_lg);
        }
    }
    
    $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
    $fullname = basename($file->getClientOriginalName());
    $ext =$file->getClientOriginalExtension();
    $size =$file->getSize();
    $mimeType = $file->getMimeType();

    $year =carbon::now()->format('Y');
    $month =carbon::now()->format('M');
    $folder = $month.'_'.$year;

    $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
    $path ="medies/".$folder;
    $fullpath ="public/medies/".$folder.'/'.$img;
    $media->src_type=$srcType;
    $media->use_Of_file=$fileUse;
    $media->src_id=$src;
    $media->file_name=Str::limit($fullname,250);
    $media->alt_text=Str::limit($name,250);
    $media->file_rename=Str::limit($img,100);
    $media->file_size=$size;
    $media->mine_type=$mimeType;
    if($ext=='png' || $ext=='jpeg' || $ext=='svg' || $ext=='gif' || $ext=='jpg' || $ext=='webp'){
      $media->file_type=1;
      }elseif($ext=='pdf'){
      $media->file_type=2;
      }elseif($ext=='docx'){
      $media->file_type=3;
      }elseif($ext=='zip' || $ext=='rar'){
      $media->file_type=4;
      }elseif($ext=='mp4' || $ext=='webm' || $ext=='mov' || $ext=='wmv'){
      $media->file_type=5;
      }elseif($ext=='mp3'){
      $media->file_type=6;
    }
    $file->move(public_path($path), $img);
    $media->file_url =$fullpath;
    $media->file_path =$path;
    $media->addedby_id=$author;
    $media->save();

    return $media;

}
}


// Saves an uploaded image as a WebP file in the media library (the media row works like uploadFile's:
// $fileStatus true replaces the file the source already has). $size makes a square of that many pixels
// cropped from the centre; without it the image is only shrunk to 2000px.
if (!function_exists('uploadImageAsWebp')) {
function uploadImageAsWebp($file,$src,$srcType,$fileUse,$author=null,$fileStatus=true,$size=null,$quality=85){

  $image =imageManager()->make($file->getRealPath());
  if(function_exists('exif_read_data')){
    $image->orientate();
  }
  if($size){
    $image->fit($size,$size);
  }else{
    $image->resize(2000,2000,function($constraint){
      $constraint->aspectRatio();
      $constraint->upsize();
    });
  }
  $data =(string) $image->encode('webp',$quality);

  return saveWebpUpload($data,$file,$src,$srcType,$fileUse,$author,$fileStatus);
}
}

// Stores WebP bytes made from an uploaded file in the media library (see uploadImageAsWebp).
if (!function_exists('saveWebpUpload')) {
function saveWebpUpload($data,$file,$src,$srcType,$fileUse,$author=null,$fileStatus=true){

  // The temp file sits inside the project so that moving it into public/ is a plain rename.
  $tmp =tempnam(storage_path('app'),'webp');
  file_put_contents($tmp,$data);
  $name =pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME) ?: 'image';
  $webp =new \Illuminate\Http\UploadedFile($tmp,$name.'.webp','image/webp',null,true);

  $media =uploadFile($webp,$src,$srcType,$fileUse,$author,$fileStatus);
  if(file_exists($tmp)){
    unlink($tmp);
  }

  // Old sized copies were deleted with the old image; they point to the new file now.
  $media->file_url_sm =$media->file_url;
  $media->file_url_md =$media->file_url;
  $media->file_url_lg =$media->file_url;
  $media->save();

  return $media;
}
}

// Ad photos: shrunk to at most 1280px and saved as WebP of at most $maxKb (a normal photo lands at about
// 50-80 KB), so pages load fast while the photo still looks sharp. The best quality that fits is used;
// only if even a low quality is too big is the photo made a bit smaller. Any format GD can read works
// (JPG, PNG, GIF, WebP, BMP, AVIF); others (HEIC, TIFF, SVG ...) are turned into JPG by the browser
// before upload, and if one still arrives the seller gets a clear message instead of a broken image.
if (!function_exists('uploadCompressedImage')) {
function uploadCompressedImage($file,$src,$srcType,$fileUse,$author=null,$fileStatus=true,$maxKb=80){

  // A 12 MP photo needs ~50 MB once decoded; make sure there is room for it.
  $memory =trim((string) ini_get('memory_limit'));
  $bytes =(int) $memory * (['g'=>1073741824,'m'=>1048576,'k'=>1024][strtolower(substr($memory,-1))] ?? 1);
  if($memory!=='-1' && $bytes<536870912){
    @ini_set('memory_limit','512M');
  }

  try{
    $image =imageManager()->make($file->getRealPath());
  }catch(\Throwable $e){
    // Intervention 2 does not know some formats GD can read (AVIF ...); give GD the file directly.
    $gd =@imagecreatefromstring((string) file_get_contents($file->getRealPath()));
    if(!$gd && function_exists('imagecreatefromavif')){
      $gd =@imagecreatefromavif($file->getRealPath());
    }
    $image =$gd?imageManager()->make($gd):null;
  }
  if(!$image){
    throw \Illuminate\Validation\ValidationException::withMessages([
      'images' => '"'.$file->getClientOriginalName().'" could not be read. Please upload a JPG, PNG or WebP photo.',
    ]);
  }
  if(function_exists('exif_read_data')){
    try{ $image->orientate(); }catch(\Throwable $e){}
  }

  $limit =$maxKb*1024;
  $data =null;
  foreach([1280,1024,800,640,480,360] as $side){
    // Each pass shrinks the (already smaller) image again, so the big original is decoded only once.
    $image->resize($side,$side,function($constraint){
      $constraint->aspectRatio();
      $constraint->upsize();
    });

    // Highest quality (in steps of 5) that stays under the limit.
    $low =40; $high =90;
    while($low<=$high){
      $quality =(int) (round(($low+$high)/10)*5);
      $encoded =(string) (clone $image)->encode('webp',$quality);
      if(strlen($encoded)<=$limit){
        $data =$encoded;
        $low =$quality+5;
      }else{
        $high =$quality-5;
      }
    }
    if($data!==null){
      break;
    }
  }
  if($data===null){
    $data =(string) $image->encode('webp',40);
  }
  $image->destroy();

  return saveWebpUpload($data,$file,$src,$srcType,$fileUse,$author,$fileStatus);
}
}


// Makes the HTML of a user written article safe for the public blog page: only simple formatting stays
// (paragraphs, headings, lists, bold / italic, quotes, links to http(s) / mailto). Scripts, styles, frames,
// forms, images, event handlers (onclick ...) and javascript: links are removed.
if (!function_exists('sanitizeArticleHtml')) {
function sanitizeArticleHtml($html){

  $html =trim((string) $html);
  if($html===''){
    return '';
  }

  $keep =['p','br','strong','b','em','i','u','s','ul','ol','li','h2','h3','h4','blockquote','a','span','div','pre','code','hr'];
  $remove =['script','style','iframe','object','embed','form','input','button','textarea','select','option','link','meta','svg','math','base','noscript','video','audio','img','canvas','template'];

  $dom =new \DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML('<?xml encoding="utf-8" ?><body><div>'.$html.'</div></body>',LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
  libxml_clear_errors();

  $clean =function(\DOMNode $node) use (&$clean,$keep,$remove){
    foreach(iterator_to_array($node->childNodes) as $child){
      if($child instanceof \DOMComment){
        $node->removeChild($child);
      }elseif($child instanceof \DOMElement){
        $tag =strtolower($child->tagName);
        if(in_array($tag,$remove,true)){
          $node->removeChild($child);
        }elseif(!in_array($tag,$keep,true)){
          // an unknown tag: its content stays, the tag goes
          $clean($child);
          while($child->firstChild){
            $node->insertBefore($child->firstChild,$child);
          }
          $node->removeChild($child);
        }else{
          foreach(iterator_to_array($child->attributes) as $attribute){
            $name =strtolower($attribute->name);
            $ok =$tag=='a' && ($name=='href' || $name=='title');
            if($ok && $name=='href' && !preg_match('#^(https?://|mailto:|/|\#)#i',trim($attribute->value))){
              $ok =false;
            }
            if(!$ok){
              $child->removeAttribute($attribute->name);
            }
          }
          if($tag=='a' && $child->hasAttribute('href')){
            $child->setAttribute('rel','noopener nofollow');
            $child->setAttribute('target','_blank');
          }
          $clean($child);
        }
      }
    }
  };

  $root =$dom->getElementsByTagName('div')->item(0);
  $clean($root);

  $out ='';
  foreach($root->childNodes as $child){
    $out .=$dom->saveHTML($child);
  }

  return trim($out);
}
}

if (!function_exists('categoryAdCount')) {
function categoryAdCount($identifier = null) {
    static $countsMap = null;
    static $slugMap = null;

    if ($countsMap === null) {
        $countsMap = \App\Models\Post::live()
            ->selectRaw('category_id, count(*) as total')
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->pluck('total', 'category_id')
            ->all();

        $slugMap = \App\Models\Attribute::where('type', 0)
            ->where('status', 'active')
            ->whereNull('parent_id')
            ->pluck('id', 'slug')
            ->all();
    }

    if ($identifier === null || $identifier === '' || $identifier === 'all') {
        return \App\Models\Post::live()->count();
    }

    $id = is_numeric($identifier) ? (int) $identifier : ($slugMap[$identifier] ?? null);

    if ($id && isset($countsMap[$id])) {
        return (int) $countsMap[$id];
    }

    return 0;
}
}

