@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Category</h5>
    <div class="card-body">
      <form method="post" action="{{route('category.update',$category->id)}}">
        @csrf 
        @method('PATCH')
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Name <span class="text-danger">*</span></label>
            <input id="inputTitle" type="text" name="name" placeholder="Enter title"  value="{{$category->name}}" class="form-control">
            @error('title')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-control">
                <option value="active" {{(($category->status=='active')? 'selected' : '')}}>Active</option>
                <option value="inactive" {{(($category->status=='inactive')? 'selected' : '')}}>Inactive</option>
            </select>
            @error('status')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush

@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
@endpush