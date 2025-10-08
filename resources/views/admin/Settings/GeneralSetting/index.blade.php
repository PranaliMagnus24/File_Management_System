@extends('admin.layouts.layout')

@section('title', 'General Settings')
@section('admin')
@section('pagetitle','General Settings')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body">
                    <form class="row g-3 mt-3" method="POST" action="{{ route('admin.store.generalSetting')}}" enctype="multipart/form-data">
                        @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">Website Name</label>
                        <input type="text" class="form-control" name="website_name" placeholder="Your website name" value="{{ $getRecord->website_name }}">
                        @error('website_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="inputName5" class="form-label">Website Url</label>
                        <input type="text" class="form-control" name="website_url" placeholder="Your website Url" value="{{ $getRecord->website_url }}">
                        @error('website_url')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="inputName5" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Your website email" value="{{ $getRecord->email }}">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="inputName5" class="form-label">Mobile No.</label>
                        <input type="text" class="form-control" name="phone" placeholder="Please enter your number" value="{{ $getRecord->phone }}">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="inputName5" class="form-label">GST Number</label>
                        <input type="text" class="form-control" name="gst_number" placeholder="GST Number" value="{{ $getRecord->gst_number }}">
                        @error('gst_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="inputName5" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" placeholder="Please enter your address" value="{{ $getRecord->address }}">
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="inputName5" class="form-label">Website Description</label>
                        <textarea class="form-control" placeholder="Description" id="floatingTextarea" style="height: 100px;" name="description">{{ $getRecord->description }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="inputName5" class="form-label">Google Location</label>
                        <textarea name="location_url" id="floatingTextarea" class="form-control" style="height: 100px;">https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3749.852683570587!2d73.7903128!3d19.9726967!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bddeb9bb1f22a2d%3A0x41f9143d797eea7d!2sMagnus%20Ideas%20Pvt%20Ltd!5e0!3m2!1sen!2sin!4v1736239480503!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">

                        </textarea>
                        @error('location')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="inputName5" class="form-label">Favicon</label>
                        <input type="file" class="form-control" name="favicon_logo" id="staticLogo">
                        @if(!empty($getRecord->favicon_logo))
                        @if(file_exists('upload/general_setting/'.$getRecord->favicon_logo))<img src="{{url('upload/general_setting/'.$getRecord->favicon_logo)}}" style="height:100px; width:100px;">
                        @endif
                        @endif
                    </div>
                    <div class="col-4">
                        <label for="inputName5" class="form-label">Header Logo</label>
                        <input type="file" class="form-control" name="header_logo" id="staticLogo">
                        @if(!empty($getRecord->header_logo))
                        @if(file_exists('upload/general_setting/'.$getRecord->header_logo))<img src="{{url('upload/general_setting/'.$getRecord->header_logo)}}" style="height:100px; width:100px;">
                        @endif
                        @endif
                    </div>
                    <div class="col-4">
                        <label for="inputName5" class="form-label">Footer Logo</label>
                        <input type="file" class="form-control" name="footer_logo" id="staticLogo">
                        @if(!empty($getRecord->footer_logo))
                        @if(file_exists('upload/general_setting/'.$getRecord->footer_logo))<img src="{{url('upload/general_setting/'.$getRecord->footer_logo)}}" style="height:100px; width:100px;">
                        @endif
                        @endif
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div></div>
@endsection
