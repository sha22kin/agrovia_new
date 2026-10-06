@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Listing ad Edit')}}</title>
@endsection @push('css')

<style type="text/css">
    .catagorydiv {
        max-height: 300px;
        overflow: auto;
    }
    .catagorydiv ul {
        padding-left: 20px;
    }
    .catagorydiv ul li {
        list-style: none;
    }
</style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Listing ad Edit</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Listing ad Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
            <a class="btn btn-outline-primary" href="{{route('admin.services')}}">BACK</a>
            <a class="btn btn-outline-primary" href="{{route('admin.servicesAction',['edit',$service->id])}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        @include('admin.alerts')
        <form action="{{route('admin.servicesAction',['update',$service->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Listing ad Edit</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Category *</label>
                                    <select class="form-control" name="category" required="">
                                        <option value="">Select Option</option>
                                        @foreach($categories as $ctg)
                                        <option value="{{$ctg->id}}"
                                        
                                        @if(old('category') == $ctg->id) 
                                            selected 
                                        @elseif($service->category_id == $ctg->id) 
                                            selected 
                                        @endif
                                        
                                        >{{$ctg->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Ad Title *</label>
                                    <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter ads Title" value="{{$service->name?:old('name')}}" required="" />
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6 form-group">
                                        <label>Ad Price *</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="Enter ad price" value="{{old('price')?:$service->price}}" name="price" required="">
                                            <label class="input-group-text"><input type="checkbox" name="negotiable" {{$service->negotiable?'checked':''}} style="margin-right: 5px;"> Negotiable</label>
                                        </div>
                                        @if ($errors->has('price'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('price') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Select Area *</label>
                                        <div class="input-group">
                                            <select id="district" class="form-control" name="district" required="">
                                                <option value="">Select District</option>
                                                @foreach(geoData(3) as $district)
                                                <option value="{{$district->id}}" {{$service->district_id == $district->id?'selected':''}} >{{$district->name}}</option>
                                                @endforeach
                                            </select>
                                            <select id="city" class="form-control" name="thana" required="">
                                                <option value="" >Select Thana</option>
                                                @if($service->district_id)
                                                @foreach(geoData(4,$service->district_id) as $thana)
                                                <option value="{{$thana->id}}" {{$service->thana_id == $thana->id?'selected':''}} >{{$thana->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('district'))
                                         <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('district') }}</p>
                                        @endif
                                        @if ($errors->has('thana'))
                                        <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('thana') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <!--<div class="form-group">-->
                                <!--    <label for="short_description">Short Description </label>-->
                                <!--    <textarea name="short_description" class="form-control {{$errors->has('short_description')?'error':''}}" placeholder="Enter Short Description">{!!$service->short_description!!}</textarea>-->
                                <!--    @if ($errors->has('short_description'))-->
                                <!--    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('short_description') }}</p>-->
                                <!--    @endif-->
                                <!--</div>-->
                                <div class="form-group">
                                    <label for="description">Description </label>
                                    <textarea name="description" rows="5" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$service->description!!}</textarea>
                                    @if ($errors->has('description'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">SEO Optimize</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="seo_title">SEO Meta Title</label>
                                    <input type="text" class="form-control {{$errors->has('seo_title')?'error':''}}" name="seo_title" placeholder="Enter SEO Meta Title" value="{{$service->seo_title?:old('seo_title')}}" />
                                    @if ($errors->has('seo_title'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_title') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="seo_desc">SEO Meta Description </label>
                                    <textarea name="seo_desc" class="form-control {{$errors->has('seo_desc')?'error':''}}" placeholder="Enter SEO Meta Description">{!!$service->seo_desc!!}</textarea>
                                    @if ($errors->has('seo_desc'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_desc') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="seo_keyword">SEO Meta Keyword </label>
                                    <textarea name="seo_keyword" class="form-control {{$errors->has('seo_keyword')?'error':''}}" placeholder="Enter SEO Meta Keyword">{!!$service->seo_keyword!!}</textarea>
                                    @if ($errors->has('seo_keyword'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('seo_keyword') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Ad Images</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <!--<div class="form-group">-->
                                <!--    <label for="image">Service Image</label>-->
                                <!--    <input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" />-->
                                <!--    @if ($errors->has('image'))-->
                                <!--    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('image') }}</p>-->
                                <!--    @endif-->
                                <!--</div>-->
                                <!--<div class="form-group">-->
                                <!--    <img src="{{asset($service->image())}}" style="max-width: 100px;" />-->
                                <!--    @isset(json_decode(Auth::user()->permission->permission, true)['services']['add'])-->
                                <!--    @if($service->imageFile)-->
                                <!--    <a href="{{route('admin.mediesDelete',$service->imageFile->id)}}" class="mediaDelete" style="color: red;"><i class="fa fa-trash"></i></a>-->
                                <!--    @endif-->
                                <!--    @endisset-->
                                <!--</div>-->
                                <div class="form-group">
                                    <label for="gallery_image">Ad images</label>
                                    <input type="file" name="gallery_image[]" class="form-control {{$errors->has('gallery_image')?'error':''}}"  multiple="" />
                                    @if ($errors->has('gallery_image'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('gallery_image') }}</p>
                                    @endif
                                </div>
                                @php
                                    $canEditPhotos = isset(json_decode(Auth::user()->permission->permission, true)['services']['add']);
                                    $mainPhoto = $service->galleryFiles->first();
                                @endphp
                                <style>
                                    .ap-main { position: relative; border: 2px solid #1ab394; border-radius: 8px; overflow: hidden; background: #f5f7fa; aspect-ratio: 4 / 3; margin-bottom: 10px; }
                                    .ap-main img { width: 100%; height: 100%; object-fit: contain; cursor: zoom-in; }
                                    .ap-badge { position: absolute; top: 6px; left: 6px; z-index: 1; background: #1ab394; color: #fff; font-size: 11px; font-weight: 600; border-radius: 10px; padding: 2px 8px; }
                                    .ap-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 6px; }
                                    .ap-item { position: relative; border: 1px solid #e3ebf3; border-radius: 6px; overflow: hidden; aspect-ratio: 1 / 1; background: #f5f7fa; }
                                    .ap-item img { width: 100%; height: 100%; object-fit: cover; cursor: zoom-in; }
                                    .ap-tools { position: absolute; top: 4px; right: 4px; display: flex; gap: 3px; }
                                    .ap-tools a { width: 24px; height: 24px; border-radius: 50%; background: rgba(255,255,255,.95); box-shadow: 0 1px 3px rgba(0,0,0,.2); display: flex; align-items: center; justify-content: center; font-size: 11px; }
                                </style>
                                <label class="d-block" style="font-weight: 600;">Main photo</label>
                                @if($mainPhoto)
                                <div class="ap-main">
                                    <span class="ap-badge"><i class="fa fa-star"></i> Main</span>
                                    <a href="{{asset($mainPhoto->file_url)}}" target="_blank" title="Open full size"><img src="{{asset($mainPhoto->file_url)}}" alt="" /></a>
                                    @if($canEditPhotos)
                                    <div class="ap-tools"><a href="{{route('admin.mediesDelete',$mainPhoto->id)}}" class="mediaDelete" style="color: red;" title="Delete" onclick="return confirm('Delete this photo?')"><i class="fa fa-trash"></i></a></div>
                                    @endif
                                </div>
                                @else
                                <p style="color: #999; font-size: 12px;">No photo yet.</p>
                                @endif

                                @if($service->galleryFiles->count() > 1)
                                <label class="d-block" style="font-weight: 600;">More photos ({{$service->galleryFiles->count() - 1}})</label>
                                <div class="ap-grid">
                                    @foreach($service->galleryFiles->slice(1) as $galley)
                                    <div class="ap-item">
                                        <a href="{{asset($galley->file_url)}}" target="_blank" title="Open full size"><img src="{{asset($galley->file_url)}}" alt="" /></a>
                                        @if($canEditPhotos)
                                        <div class="ap-tools">
                                            <a href="{{route('admin.servicesAction',['main-image',$service->id,'image_id'=>$galley->id])}}" style="color: #f6a609;" title="Set as main photo"><i class="fa fa-star"></i></a>
                                            <a href="{{route('admin.mediesDelete',$galley->id)}}" class="mediaDelete" style="color: red;" title="Delete" onclick="return confirm('Delete this photo?')"><i class="fa fa-trash"></i></a>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>

                    <!--<div class="card">-->
                    <!--    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">-->
                    <!--        <h4 class="card-title">Service Category</h4>-->
                    <!--    </div>-->
                    <!--    <div class="card-content">-->
                    <!--        <div class="card-body">-->
                    <!--            @if ($errors->has('categoryid*'))-->
                    <!--            <p style="color: red; margin: 0; font-size: 10px;">The Category Must Be a Number</p>-->
                    <!--            @endif-->
                    <!--            <div class="catagorydiv">-->
                    <!--                <ul>-->
                    <!--                    @foreach($categories as $ctg)-->
                    <!--                    <li>-->
                    <!--                        <div class="custom-control custom-checkbox">-->
                    <!--                            <input type="checkbox" class="custom-control-input" value="{{$ctg->id}}" id="category_{{$ctg->id}}" name="categoryid[]" @foreach($service->serviceCtgs as $postctg)-->
                    <!--                            {{$postctg->reff_id==$ctg->id?'checked':''}} @endforeach/>-->
                    <!--                            <label class="custom-control-label" for="category_{{$ctg->id}}">{{$ctg->name}}</label>-->
                    <!--                        </div>-->
                    <!--                        @if($ctg->subCtgs->count() >0) @include('admin.services.includes.servicesEditSubctg',['subcategories' => $ctg->subCtgs,'i'=>1]) @endif-->
                    <!--                    </li>-->
                    <!--                    @endforeach-->
                    <!--                </ul>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->


                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                            <h4 class="card-title">Ads Action</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="mb-1">Contact Number* (<small>{{$service->user?$service->user->name:'No Author'}}</small>)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Enter Contact mobile" value="{{$service->phone_number?:auth::user()->mobile}}" name="mobile" required="">
                                        <label class="input-group-text"><input type="checkbox" name="hide_phone" {{$service->hide_phone?'checked':''}} style="margin-right: 5px;"> Hide phone</label>
                                    </div>
                                    @if ($errors->has('mobile'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('mobile') }}</p>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="status">Ad Status</label>
                                        <select class="form-control" name="status">
                                            <option value="pending" {{$service->status=='pending'?'selected':''}} >Pending</option>
                                            <option value="active" {{$service->status=='active'?'selected':''}}>Active</option>
                                            <option value="solded" {{$service->status=='solded'?'selected':''}}>Solded</option>
                                            <option value="cancelled" {{$service->status=='cancelled'?'selected':''}}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="fetured">Ad Featured</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="fetured" name="fetured" {{$service->fetured?'checked':''}}/>
                                            <label class="custom-control-label" for="fetured">Active</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Published Date</label>
                                    <input type="date" class="form-control form-control-sm" name="created_at" value="{{$service->created_at->format('Y-m-d')}}">
                                    @if ($errors->has('created_at'))
                                    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('created_at') }}</p>
                                    @endif
                                </div>
                                @isset(json_decode(Auth::user()->permission->permission, true)['services']['add'])
                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Basic Inputs end -->
</div>

@endsection @push('js')

<script>
    $(".summernote").summernote({
        placeholder: "Hello stand alone ui",
        tabsize: 2,
        height: 120,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture"]],
            ["view", ["fullscreen", "codeview"]],
        ],
    });
</script>

@endpush
