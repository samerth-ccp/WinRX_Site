@extends('Backend.layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.default.min.css" rel="stylesheet">
@endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') <a href="{{ route('products') }}" >Product List<a> @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post"  class="needs-validation" id="manage_form"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_image">Product Image</label>
                                <input type="file" class="form-control {{empty($product->product_image)?'required':''}}" name="product_image" id="product_image" accept="image/*">
                                @if(!empty($product->product_image))
                                    <div class="p-3" style="width: 200px">
                                        <img class="w-100" src="{{asset('assets/storage/products/'.$product->product_image)}}">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_name">Product Name</label>
                                <input type="text" class="form-control required" name="product_name" id="product_name" placeholder="Product Name" value="{{$product->product_name??old('product_name')??''}}" maxlength="200" >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_description">Product Description</label>
                                <textarea class="form-control required" name="product_description" id="product_description" placeholder="Product description..." rows="3"  maxlength="500" >{{$product->product_description??old('product_description')??''}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_color">Product Color</label>
                                <select class="required tom-select" name="product_color[]" id="product_color" multiple>
                                    <option value="">Select Color</option>
                                    @foreach($colors as $c=>$name)
                                        <option value="{{$c}}" {{!empty($product->product_color) && in_array($c, $product->product_color)?'selected':''}}>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_size">Product Size</label>.
                                <select class="required tom-select" name="product_size[]" id="product_size" multiple>
                                    <option value="">Select Size</option>
                                    @foreach($sizes as $s=>$name)
                                        <option value="{{$s}}" {{!empty($product->product_size) && in_array($s, $product->product_size)?'selected':''}}>Size: {{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_price">Product Prices (Based On Colors)</label>
                                <div id="price_list">
                                    @if(!empty($product->product_price))
                                        @foreach($product->product_price as $key=>$value)
                                            <div class="mt-2 form-label" for="product_price_{{$key}}">
                                                <label>{{$colors[$key] ?? ''}} Color Price ($)</label>
                                                <input type="number" class="form-control required onlyNumbers money_charm" name="product_price[{{$key}}]" id="product_price_{{$key}}" placeholder="{{$colors[$key] ?? ''}} Color Price" value="{{$value}}" >
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <h4>Other Informations:</h4>
                    <hr/>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_helpful_answer">Helpful Answers</label>
                                <textarea class="form-control" name="product_helpful_answer" id="product_helpful_answer" placeholder="Helpful Answers description..." rows="3"  maxlength="500" >{{$product->product_helpful_answer??old('product_helpful_answer')??''}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_faqs">Made For You (<small>Wrap into {{ '<strong>Your Text</strong>' }} for bold text.</small> | <small>Click on plus icon for add new card</small>)  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="addMFY(this)">{{ svg('css-add') }}</a></label>
                                <div id="made_for_list">
                                    @if(!empty($product->product_specification))
                                        @foreach($product->product_specification as $key=>$value)
                                            <div class="mt-2 form-label">
                                                <label>Image/Text &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="remove(this)" >{{ svg('css-trash') }}</a></label>
                                                <input type="hidden" name="product_specification[spe_{{$key}}][image]" value="{{$value['image']??''}}">
                                                <input type="file" class="form-control {{empty($value['image'])?'required':''}} mb-2" name="product_specification[spe_{{$key}}][image]">
                                                <textarea class="form-control required" rows="3" name="product_specification[spe_{{$key}}][text]" placeholder="description..." maxlength="200">{{$value['text']??''}}</textarea>

                                                @if(!empty($value['image']))
                                                    <div class="p-3" style="width: 200px">
                                                        <img class="w-100" src="{{asset('assets/storage/products/'.$value['image'])}}">
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_faqs">Faq's/Image (<small>Click on plus icon for add new faq</small>)  &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="addFaq(this)">{{ svg('css-add') }}</a></label>
                                <div id="faq_list">
                                    @if(!empty($product->product_faqs))
                                        @foreach($product->product_faqs as $key=>$value)
                                            <div class="mt-2 form-label">
                                                <label>Question/Answer &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="remove(this)" >{{ svg('css-trash') }}</a></label>
                                                <input type="text" class="form-control required mb-2" name="product_faqs[faq_{{$key}}][question]" value="{{$value['question']??''}}" placeholder="Question" maxlength="200">
                                                <textarea class="form-control required" rows="3" name="product_faqs[faq_{{$key}}][answer]" placeholder="Answers description..." maxlength="500">{{$value['answer']??''}}</textarea>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="file" class="form-control {{empty($product->product_faq_image)?'required':''}}" name="product_faq_image" id="product_faq_image" accept="image/*">
                                @if(!empty($product->product_faq_image))
                                    <div class="p-3" style="width: 200px">
                                        <img class="w-100" src="{{asset('assets/storage/products/'.$product->product_faq_image)}}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr/>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_in_box">What’s In-box/Image</label>
                                <input type="text" class="required" name="product_in_box" id="product_in_box" placeholder="What in Box" value="{{$product->product_in_box??old('product_in_box')??''}}" maxlength="200" >
                            </div>

                            <div class="mb-3">
                                <input type="file" class="form-control {{empty($product->product_in_box_image)?'required':''}}" name="product_in_box_image" id="product_in_box_image" accept="image/*">
                                @if(!empty($product->product_in_box_image))
                                    <div class="p-3" style="width: 200px">
                                        <img class="w-100" src="{{asset('assets/storage/products/'.$product->product_in_box_image)}}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr/>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="product_tech_insights">Tech Insights</label>
                                <textarea class="form-control required" name="product_tech_insights" id="product_tech_insights" rows="6" >{{$product->product_tech_insights??old('product_tech_insights')??$ediContent}}</textarea>
                            </div>
                        </div>

                    </div>


                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
<script>
    $(document).ready(function(){

        $('.tom-select').each(function(index, elm){
            new TomSelect(elm,{
                persist: false,
                createOnBlur: true,
                create: false,
                plugins: {
                    remove_button:{
                        title:'Remove this item',
                    }
                },
                onItemAdd: function(value){
                    if(elm.id == 'product_color'){

                        $('#price_list')
                        .append(`
                            <div class="mt-2 form-label" for="product_price_${value}">
                                <label>${$('#product_color').find('option[value="'+value+'"]').text()} Color Price ($)</label>
                                <input type="number" class="form-control required onlyNumbers money_charm" name="product_price[${value}]" id="product_price_${value}" placeholder="${$('#product_color').find('option[value="'+value+'"]').text()} Color Price" value="" >
                            </div>
                        `);
                    }
                },
                onItemRemove: function(value){
                    if(elm.id == 'product_color'){
                        $('#product_price_'+value).parent('div.mt-2').remove();
                    }
                },
            });
        });

        new TomSelect("#product_in_box",{
            persist: false,
            createOnBlur: true,
            create: true,
            plugins: {
                remove_button:{
                    title:'Remove this item',
                }
            },
        });

        $('#manage_form').validate();

        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
        CKEDITOR.replace('product_tech_insights',{
            //contentsCss : [''],
            toolbar:[
                [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ],
                [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
                [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ],
                [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ],
                [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ],
                [ 'Link', 'Unlink', 'Anchor' ],
                [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ],
                [ 'Styles', 'Format', 'Font', 'FontSize' ], ['Youtube'],
                [ 'TextColor', 'BGColor' ],
                [ 'Maximize', 'ShowBlocks' ],
            ],
            height: 380
        });
    });

    let x = $('#faq_list').find('.form-label').length;
    const addFaq = () => {
        x++;
        $('#faq_list')
        .append(
            `
                <div class="mt-2 form-label">
                    <label>Question/Answer &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="remove(this)" >{{ svg('css-trash') }}</a></label>
                    <input type="text" class="form-control required mb-2" name="product_faqs[${x}][question]" placeholder="Question" maxlength="200">
                    <textarea class="form-control required" rows="3" name="product_faqs[${x}][answer]" placeholder="Answers description..." maxlength="500"></textarea>
                </div>
            `
        );
    }

    const remove = (that) => {
        $(that).parents('div.mt-2').remove();
    }

    let y = $('#made_for_list').find('.form-label').length;
    const addMFY = () => {
        y++;
        $('#made_for_list')
        .append(
            `
                <div class="mt-2 form-label">
                    <label>Image/Text &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="remove(this)" >{{ svg('css-trash') }}</a></label>
                    <input type="file" class="form-control required mb-2" name="product_specification[${y}][image]">
                    <textarea class="form-control required" rows="3" name="product_specification[${y}][text]" placeholder="description..." maxlength="200"></textarea>
                </div>
            `
        );
    }

    $(document).on('input', '.money_charm', function() {
    let val = $(this).val();

    if (val === '') return;

    // Allow user to type just a dot (".") temporarily
    if (val === '.') return;

    let num = parseFloat(val);

    if (isNaN(num) || num === 0) {
        $(this).val('');
    }
});

</script>
@endsection
