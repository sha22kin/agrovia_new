@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}">
@endsection
@push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$page->name}}</h1>
    </div>
</div>

<div class="sellSomethingPage">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="sellFormBox">
                    <form action="" method="">
                        <div class="groupBox">
                            <label>* Title</label>
                            <input class="form-control" placeholder="" name="title" type="text" required />
                        </div>
                        <hr>
                        <div class="groupBox">
                            <h5>* Condition</h5>
                            <label for="used" class="radio-inline">
                                <input type="radio" value="used" id="used" checked>Used
                            </label>
                            <label for="connew" class="radio-inline">
                                <input type="radio" value="connew" id="connew">New
                            </label>
                        </div>
                        <hr>
                        <div class="groupBox">
                            <h5>* Party</h5>
                            <label for="direct" class="radio-inline">
                                <input type="radio" value="direct" id="direct" checked>Direct
                            </label>
                            <label for="parnew" class="radio-inline">
                                <input type="radio" value="parnew" id="parnew">New
                            </label>
                        </div>
                        <hr>
                        <div class="groupBox">
                            <label>* Description</label>
                            <textarea rows="7" class="form-control" placeholder="" name="description" type="text" required></textarea>
                        </div>
                        
                        <div class="groupBox">
                            <label>* Price (TK.)</label>
                            <input class="form-control" placeholder="" name="price" type="text" required />
                            <label class="radio-inline">
                                <input type="checkbox">Nagotiable
                            </label>
                        </div>
                        <hr>
                        <div class="groupBox">
                            <label>* Add upto 5 photos</label>
                            <div class="photoUp">
                                <div class="upload-btn-wrapper">
                                    <button class="uploadBtn"><span><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M457.6 140.2l-82.5-4-4.8-53.8c-1-11.3-11.1-19.2-22.9-18.3L51.5 88.4c-11.8 1-20.3 10.5-19.4 21.7l21.2 235.8c1 11.3 11.2 19.2 22.9 18.3l15-1.2-2.4 45.8c-.6 12.6 9.2 22.8 22.4 23.5L441.3 448c13.2.6 24.1-8.6 24.8-21.2L480 163.5c.6-12.5-9.3-22.7-22.4-23.3zm-354.9 5.3l-7.1 134.8L78.1 305 62 127v-.5-.5c1-5 4.4-9 9.6-9.4l261-21.4c5.2-.4 9.7 3 10.5 7.9 0 .2.3.2.3.4 0 .1.3.2.3.4l2.7 30.8-219-10.5c-13.2-.4-24.1 8.8-24.7 21.3zm334 236.9l-84.8-99.5-37.4 34.3-69.2-80.8-122.7 130.7L133 168v-.4c1-5.4 6.2-9.3 11.9-9l291.2 14c5.8.3 10.3 4.7 10.4 10.2 0 .2.3.3.3.5l-10.1 199.1z"></path><path d="M384 256c17.6 0 32-14.4 32-32s-14.3-32-32-32c-17.6 0-32 14.3-32 32s14.3 32 32 32z"></path></svg></span>Add a Photo</button>
                                    <input type="file" name="myfile" multiple="">
                                    <button class="uploadBtn"><span><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M457.6 140.2l-82.5-4-4.8-53.8c-1-11.3-11.1-19.2-22.9-18.3L51.5 88.4c-11.8 1-20.3 10.5-19.4 21.7l21.2 235.8c1 11.3 11.2 19.2 22.9 18.3l15-1.2-2.4 45.8c-.6 12.6 9.2 22.8 22.4 23.5L441.3 448c13.2.6 24.1-8.6 24.8-21.2L480 163.5c.6-12.5-9.3-22.7-22.4-23.3zm-354.9 5.3l-7.1 134.8L78.1 305 62 127v-.5-.5c1-5 4.4-9 9.6-9.4l261-21.4c5.2-.4 9.7 3 10.5 7.9 0 .2.3.2.3.4 0 .1.3.2.3.4l2.7 30.8-219-10.5c-13.2-.4-24.1 8.8-24.7 21.3zm334 236.9l-84.8-99.5-37.4 34.3-69.2-80.8-122.7 130.7L133 168v-.4c1-5.4 6.2-9.3 11.9-9l291.2 14c5.8.3 10.3 4.7 10.4 10.2 0 .2.3.3.3.5l-10.1 199.1z"></path><path d="M384 256c17.6 0 32-14.4 32-32s-14.3-32-32-32c-17.6 0-32 14.3-32 32s14.3 32 32 32z"></path></svg></span>Add a Photo</button>
                                    <input type="file" name="myfile" multiple="">
                                    <button class="uploadBtn"><span><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M457.6 140.2l-82.5-4-4.8-53.8c-1-11.3-11.1-19.2-22.9-18.3L51.5 88.4c-11.8 1-20.3 10.5-19.4 21.7l21.2 235.8c1 11.3 11.2 19.2 22.9 18.3l15-1.2-2.4 45.8c-.6 12.6 9.2 22.8 22.4 23.5L441.3 448c13.2.6 24.1-8.6 24.8-21.2L480 163.5c.6-12.5-9.3-22.7-22.4-23.3zm-354.9 5.3l-7.1 134.8L78.1 305 62 127v-.5-.5c1-5 4.4-9 9.6-9.4l261-21.4c5.2-.4 9.7 3 10.5 7.9 0 .2.3.2.3.4 0 .1.3.2.3.4l2.7 30.8-219-10.5c-13.2-.4-24.1 8.8-24.7 21.3zm334 236.9l-84.8-99.5-37.4 34.3-69.2-80.8-122.7 130.7L133 168v-.4c1-5.4 6.2-9.3 11.9-9l291.2 14c5.8.3 10.3 4.7 10.4 10.2 0 .2.3.3.3.5l-10.1 199.1z"></path><path d="M384 256c17.6 0 32-14.4 32-32s-14.3-32-32-32c-17.6 0-32 14.3-32 32s14.3 32 32 32z"></path></svg></span>Add a Photo</button>
                                    <input type="file" name="myfile" multiple="">
                                    <button class="uploadBtn"><span><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M457.6 140.2l-82.5-4-4.8-53.8c-1-11.3-11.1-19.2-22.9-18.3L51.5 88.4c-11.8 1-20.3 10.5-19.4 21.7l21.2 235.8c1 11.3 11.2 19.2 22.9 18.3l15-1.2-2.4 45.8c-.6 12.6 9.2 22.8 22.4 23.5L441.3 448c13.2.6 24.1-8.6 24.8-21.2L480 163.5c.6-12.5-9.3-22.7-22.4-23.3zm-354.9 5.3l-7.1 134.8L78.1 305 62 127v-.5-.5c1-5 4.4-9 9.6-9.4l261-21.4c5.2-.4 9.7 3 10.5 7.9 0 .2.3.2.3.4 0 .1.3.2.3.4l2.7 30.8-219-10.5c-13.2-.4-24.1 8.8-24.7 21.3zm334 236.9l-84.8-99.5-37.4 34.3-69.2-80.8-122.7 130.7L133 168v-.4c1-5.4 6.2-9.3 11.9-9l291.2 14c5.8.3 10.3 4.7 10.4 10.2 0 .2.3.3.3.5l-10.1 199.1z"></path><path d="M384 256c17.6 0 32-14.4 32-32s-14.3-32-32-32c-17.6 0-32 14.3-32 32s14.3 32 32 32z"></path></svg></span>Add a Photo</button>
                                    <input type="file" name="myfile" multiple="">
                                    <button class="uploadBtn"><span><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M457.6 140.2l-82.5-4-4.8-53.8c-1-11.3-11.1-19.2-22.9-18.3L51.5 88.4c-11.8 1-20.3 10.5-19.4 21.7l21.2 235.8c1 11.3 11.2 19.2 22.9 18.3l15-1.2-2.4 45.8c-.6 12.6 9.2 22.8 22.4 23.5L441.3 448c13.2.6 24.1-8.6 24.8-21.2L480 163.5c.6-12.5-9.3-22.7-22.4-23.3zm-354.9 5.3l-7.1 134.8L78.1 305 62 127v-.5-.5c1-5 4.4-9 9.6-9.4l261-21.4c5.2-.4 9.7 3 10.5 7.9 0 .2.3.2.3.4 0 .1.3.2.3.4l2.7 30.8-219-10.5c-13.2-.4-24.1 8.8-24.7 21.3zm334 236.9l-84.8-99.5-37.4 34.3-69.2-80.8-122.7 130.7L133 168v-.4c1-5.4 6.2-9.3 11.9-9l291.2 14c5.8.3 10.3 4.7 10.4 10.2 0 .2.3.3.3.5l-10.1 199.1z"></path><path d="M384 256c17.6 0 32-14.4 32-32s-14.3-32-32-32c-17.6 0-32 14.3-32 32s14.3 32 32 32z"></path></svg></span>Add a Photo</button>
                                    <input type="file" name="myfile" multiple="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="contactDetrails">
                            <h4>Contact Details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="groupBox">
                                        <label>Name</label>
                                        <input class="form-control" placeholder="" name="name" type="name" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="groupBox">
                                        <label>Email</label>
                                        <input class="form-control" placeholder="" name="email" type="email" required />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="groupBox">
                                        <label>Address</label>
                                        <input class="form-control" placeholder="" name="address" type="text" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="groupBox">
                                        <div class="input-group">
                                            <label>Phone</label>
                                            <input class="form-control" placeholder="" name="phone" type="phone" required />
                                            <span class="input-group-text" id="basic-addon2">Add</span>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                    <a href="#" class="addNumBtn">Add</a>
                                </div>--}}
                                <div class="col-md-12">
                                    <div class="groupBox">
                                        <label class="radio-inline">
                                            <input type="checkbox">I have read and accept the Terms and Conditions
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="postAdBtn">Post Ad</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush


