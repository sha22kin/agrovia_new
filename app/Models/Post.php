<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    //Models Information Data
    /********
     * 
     * type ==0 : Page
     * type ==1 : Post
     * type ==3 : Service
     * 
     * ------------------------
     *  Status==temp, active, inactive
     * ------------------------
     * 
     * Column:
     * 
     * id            =bigint(20):None,
     * name          =varchar(200):null,
     * slug          =varchar(250):null,
     * short_description =text:null,
     * description   =longText:null,
     * view          =bigint(20):0
     * type          =int(1):0
     * seo_title     =varchar(191):null
     * seo_desc      =text:null
     * seo_keyword   =text:null
     * search_key    =text:null
     * status        =varchar(10):null
     * featured       =tinyInt(1):null
     * addedby_id    =bigint(20):null
     * editedby_id   =bigint(20)::null
     * created_at    =timestamp:null
     * updated_at    =timestamp:null
     * 
     * 
     * 
     ****/


    //Image and Banner Functions Start
    /********
     * 
     * *********/

    public function imageFile(){
    	return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',1);
    }

    public function image(){
        if($this->imageFile){
            return $this->imageFile->file_url;
        }else{
            return 'public/medies/noimage.jpg';
        }
    }
    
    public function fiImage(){
        if($img =$this->galleryFiles->first()){
            return $img->file_url;
        }else{
            return 'public/medies/noimage.jpg';
        }
    }
    
    public function imageName(){
        
        if($this->imageFile){
            return $this->imageFile->file_rename;
        }else{
            return 'noimage.jpg';
        }
    }

    public function bannerFile(){
        return $this->hasOne(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',2);
    }

    public function banner(){
        if($this->bannerFile){
            return $this->bannerFile->file_url;
        }else{
            return 'public/medies/no-banner.png';
        }
    }
    
    public function bannerName(){
        
        if($this->bannerFile){
            return $this->bannerFile->file_rename;
        }else{
            return 'no-banner.png';
        }
    }

    // Ad photos in their order; the first one is the main (cover) photo.
    public function galleryFiles(){
        return $this->hasMany(Media::class,'src_id')->where('src_type',1)->where('use_Of_file',3)->orderBy('drag')->orderBy('id');
    }

    // Puts a photo first (main photo); the others keep their order behind it.
    public function setMainPhoto($mediaId){
        $photos =$this->galleryFiles()->get();
        $main =$photos->firstWhere('id',(int) $mediaId);
        if(!$main){
            return false;
        }
        $ordered =collect([$main])->concat($photos->reject(fn ($m) => $m->id==$main->id));
        foreach($ordered->values() as $i=>$photo){
            if($photo->drag!=$i){
                $photo->drag =$i;
                $photo->save();
            }
        }
        $this->unsetRelation('galleryFiles');
        return true;
    }

    // New photos go after the existing ones.
    public function nextPhotoPosition(){
        return (int) $this->galleryFiles()->max('drag') + ($this->galleryFiles()->exists() ? 1 : 0);
    }

    //Image and Banner Functions End


    //Post Category tag, comments Functions 
    public function postCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1)->orderBy('drag','asc');
    }

    public function postTags(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2)->orderBy('drag','asc');
    }

    public function postComments(){
        return $this->hasMany(Review::class,'src_id')->where('type',1);
    }
    
    public function postCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',1)->orderBy('drag', 'asc');
    }
    
     public function Tags(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',2)->orderBy('drag', 'asc');
    }

    public function ctgPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',1);
    }

    public function tagPosts(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',2);
    }

    public function relatedPosts(){
        return Post::whereHas('ctgPosts',function($q){
                    $q->whereIn('reff_id',$this->ctgPosts->pluck('reff_id'));
                })
                ->whereNot('id',$this->id)
                ->where('status','active')
                ->whereDate('created_at','<=',date('Y-m-d'));
 
    }

    //Post Category tag, comments Functions End


    //Services Category Functions 
    public function serviceCtgs(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0)->orderBy('drag','asc');
    }

    public function serviceCategories(){
        return $this->belongsToMany(Attribute::class, PostAttribute::class,'src_id','reff_id')->wherePivot('type',0)->orderBy('drag', 'asc');
    }

    public function ctgServices(){
        return $this->hasMany(PostAttribute::class,'src_id')->where('type',0);
    }
    
    // Ads a visitor may see: type 3, active and already published.
    public function scopeLive($query){
        return $query->where('type',3)->where('status','active')->whereDate('created_at','<=',date('Y-m-d'))
        ->whereHas('user',fn ($q) => $q->notDeactivated());
    }

    public function scopeKeyword($query, $keyword){
        return $query->where(function($q) use($keyword){
            $q->where('name','LIKE','%'.$keyword.'%')
            ->orWhere('search_key','LIKE','%'.$keyword.'%');
        });
    }

    // Narrows the ads down by category, district and price; empty values are ignored.
    public function scopeFilteredBy($query, $category=null, $district=null, $min=null, $max=null){
        return $query
        ->when(is_numeric($category),fn ($q) => $q->where('category_id',$category))
        ->when(is_numeric($district),fn ($q) => $q->where('district_id',$district))
        ->when(is_numeric($min),fn ($q) => $q->where('price','>=',$min))
        ->when(is_numeric($max),fn ($q) => $q->where('price','<=',$max));
    }

    // Live ads found by a search: the keyword is required, the rest narrows it down.
    public function scopeMatching($query, $keyword, $category=null, $district=null, $min=null, $max=null){
        return $query->live()
        ->when($keyword,fn ($q) => $q->keyword($keyword),fn ($q) => $q->whereRaw('1 = 0'))
        ->filteredBy($category,$district,$min,$max);
    }

    public function category(){
    	return $this->belongsTo(Attribute::class,'category_id')->where('type',0);
    }
    
    public function district(){
    	return $this->belongsTo(Country::class,'district_id')->where('type',3);
    }
    
    public function thana(){
    	return $this->belongsTo(Country::class,'thana_id')->where('type',4);
    }
    
    public function area(){
        $add =null;
        
        if($this->thana){
           $add .=$this->thana->name;
        }
        if($this->thana && $this->district){
           $add .=', ';
        }
        if($this->district){
           $add .=$this->district->name;
        }
        
        return $add;
        
    }
    
    //Services Category Functions End
    
    public function hasNitEditorContent(){
    	return $this->hasMany(PostContentElement::class,'src_id')->where('parent_id',null)->orderBy('drag');
    }
    
    
    public function user(){
    	return $this->belongsTo(User::class,'addedby_id');
    }

    

    

    
}
