@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Brand infomation</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.brands') }}">
                            <div class="text-tiny">Brands</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Brand</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.brand.update', $brand->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <input type="hidden" name="id" value="{{ $brand->id }}">
                    {{-- brand name --}}
                    <fieldset class="name">
                        <div class="body-title">Brand Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Brand name" name="name" tabindex="0"
                            value="{{ $brand->name }}" aria-required="true" required="">
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- brand slug --}}
                    <fieldset class="name">
                        <div class="body-title">Brand Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Brand Slug" name="slug" tabindex="0"
                            value="{{ $brand->slug }}" aria-required="true" required="">
                    </fieldset>

                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    {{-- brand image --}}
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            @if (!empty($brand->image))
                                <div class="item" id="imgpreview">
                                    <img src="{{ asset('uploads/brands/' . $brand->image) }}" class="effect8"
                                        alt="{{ $brand->name }}">
                                </div>
                            @endif


                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // File input change event to preview image
            $("#myFile").on("change", function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files; // Destructure to get the first file
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(
                        file)); // Preview the selected image
                    $("#imgpreview").show(); // Show the preview section
                }
            });

            // Name input change event to generate slug
            $("input[name='name']").on("change", function() {
                $("input[name='slug']").val(StringToSlug($(this).val())); // Generate and set the slug
            });
        });

        // Function to convert text to a URL-friendly slug
        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+/g, "") // Remove special characters
                .replace(/ +/g, "-"); // Replace spaces with hyphens
        }
    </script>
@endpush
