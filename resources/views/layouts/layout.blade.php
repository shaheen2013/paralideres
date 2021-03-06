<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- TITLE -->
    @if(isset($resource->title))
        <title>{{ $resource->title }} - {{$author['username']}}</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif
    @if(isset($resource->review))
        <meta name="description" content="{{$resource->review}}">
    @else
        <meta name="description" content="">
    @endif
    @if(isset($resource->category->id))
        @if($resource->category->id == 9 || $resource->category->id == 11 || $resource->category->id ==12)
            <meta name="og:image"
                  content="{{asset('images/icon/cat-icon-12.png')}}">
            <meta name="og:image:secure_url"
                  content="{{asset('images/icon/cat-icon-12.png')}}">
        @else
            <meta name="og:image"
                  content="{{asset('images/icon/cat-icon-'.$resource->category->id.'.png')}}">
            <meta name="og:image:secure_url"
                  content="{{asset('images/icon/cat-icon-'.$resource->category->id.'.png')}}">
        @endif
    @else
        <meta name="og:image"
              content="">
        <meta name="og:image:secure_url"
              content="">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="{{asset('apple-touch-icon.png')}}">
    <link href="{{ asset('css/app.css') }}?paralideres={{uniqid()}}" rel="stylesheet">
@yield('styles')
<!-- Script for polyfilling Promises on IE9 and 10 -->
    <script src='https://cdn.polyfill.io/v2/polyfill.min.js'></script>
    <script src="{{asset('js/modernizr-2.8.3-respond-1.4.2.min.js')}}"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://selectize.github.io/selectize.js/js/selectize.js"></script>
    <![endif]-->
    <script>
        window.asset = '{{env("APP_URL")}}/';
        window.base_url = '{{env("APP_URL")}}/';
        window.img_path = '{{env("APP_URL")}}/public';
        window.api_url = 'api/v1/';
    </script>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->


<!-- =========================
    START HEADER SECTION
============================== -->
@if(isset($resource_header) && $resource_header === true)
    @include('layouts.common.resource_hearder')
@else
    @include('layouts.common.header')
@endif
<!-- =========================
        END HEADER SECTION
    ============================== -->

@yield('content')

<!-- =========================
        START SOCIAL SHARE SECTION
    ============================== -->
@include('layouts.common.social')
<!-- =========================
        END SOCIAL SHARE SECTION
    ============================== -->


<!-- =========================
    START FOOTER SECTION
============================== -->
@include('layouts.common.footer')
<!-- =========================
        END FOOTER SECTION
    ============================== -->

@if($auth)
    <div class="popup_content" id="create_resource_popup">
        <form id="create_resource_form" enctype="multipart/form-data">
            <div class="login_content create_resourse">
                <div class="step_1">
                    <h2>Crear un Recurso - Paso 1 de 3 <br>
                        <span>Completa los siguientes campos y da Click en Continuar </span>
                        <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                             alt=""></h2>
                    <div class="login_inner clearfix">

                        <div class="input_content clearfix" :class="{'has-error':errors1.title}">
                            <label>TITULO RECURSO</label>
                            <input type="text" name="title" required id="input_text" placeholder="Escribe algo aquí..."
                                   v-model="recurso.title"
                                   class="noMargin">
                            <p class="custom-err-msg">
                                <span v-if="errors1.title" v-text="errors1.title[0]"></span>
                            </p>
                        </div>
                        <div class="input_content clearfix" :class="{'has-error':errors1.review}">
                            <label>RESEÑA O RESUME</label>
                            <textarea name="review" id="" required cols="30" rows="10" class="noMargin"
                                      v-model="recurso.description"></textarea>
                            <p class="custom-err-msg">
                                <span v-if="errors1.review" v-text="errors1.review[0]"></span>
                            </p>
                        </div>
                        <div class="input_content clearfix" :class="{'has-error':errors1.category_id}">
                            <label>CATEGORIA</label>
                            <select name="category_id" class="noMargin" required v-model="recurso.cat_id"
                                    v-on:change="categorySelect">
                                <option value="">...SELECT CATEGORIA...</option>
                                @if(isset($popup_categories))
                                    @foreach($popup_categories as $category)
                                        <option value="{{$category->id}}">{{$category->label}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="custom-err-msg">
                                <span v-if="errors1.category_id" v-text="errors1.category_id[0]"></span>
                            </p>
                        </div>
                        <div class="input_content clearfix" id="old_tag">
                            <label>ETIQUETAS</label>
                            {{--{{$popup_tags}}--}}
                            <select id="select2" multiple name="tag_ids[]" required>
                                <option value="">...SELECT ETIQUETAS...</option>
                                @if(isset($popup_tags))
                                    @foreach($popup_tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->label}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="hints">Elija o cree una nueva etiqueta escribiendo una nueva</span>
                            {{--<a class="new_tag" href="#" v-on:click.prevent="newTag">Crear nueva etiqueta</a>--}}
                        </div>
                        <div class="input_content clearfix" id="new_tag">
                            <label>NUEVA ETIQUETAS</label>
                            <input type="text" name="tag" id="newTag" placeholder="NUEVA ETIQUETAS...">
                            <a class="new_tag" href="#" v-on:click.prevent="oldTag">Seleccionar etiqueta</a>
                        </div>
                        <button v-on:click.prevent="createResourceSetp1" class="resource_2">Continuar</button>
                        <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>

                    </div>
                </div>
            </div>
            <div class="login_content create_resourse">
                <div class="step_2 clearfix">
                    <h2>Crear un Recurso - Paso 2 de 3 <br> <span>Elije la Opción de tu preferencia </span>
                        <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                             alt=""></h2>
                    <div class="login_inner clearfix">

                        <div class="upload_file_area">
                            <p><span>Sube tu contenido,</span> puedes hacer con un archivo o copiando el texto. <span>Elije una opción:</span>
                            </p>
                            <div class="upload_file_wrapper clearfix">
                                <div class="upload-recurso-box" v-on:click="option2">
                                    <div class="upload-recurso-box-content">
                                        <img src="{{asset('images/file-up-img.png')}}" alt="">
                                        <p>CLICK PARA SUBIR TU ARCHIVO</p>
                                    </div>
                                </div>
                                <div class="upload-recurso-box" v-on:click="option1">
                                    <div class="upload-recurso-box-content">
                                        <img src="{{asset('images/file-up-img-2.png')}}" alt="">
                                        <p>USAR EL EDITOR DE TEXTO</p>
                                    </div>
                                </div>
                                {{--<div class="upload_text" v-on:click="option2">
                                    <div class="upload_text_inner">
                                        <img src="{{asset('images/file-up-img-2.jpg')}}" alt="">
                                        <p>CLICK PARA SUBIR TU ARCHIVO</p>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                        <p class="noMargin noPadding text-center"><a href="#" v-on:click.prevent="back1">VOLVER AL PASO
                                ANTERIOR</a></p>
                        <br><br><br>
                        {{--<button v-on:click.prevent="option2" class="resource_2">Continuar</button>--}}

                    </div>
                </div>
            </div>
            <div class="login_content create_resourse">
                <div class="step_3">
                    <h2>Crear un Recurso - Paso 3 de 3 <br> <span>Escribe el contenido de tu Colaboracion </span>
                        <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                             alt=""></h2>
                    <div class="login_inner clearfix">

                        <div class="text_area_file clearfix" :class="{'has-error':errors2.content}">
                            <p>CONTENIDO</p>
                            <textarea style="margin-bottom: 40px!important;" name="content" id="" cols="30" rows="10"
                                      placeholder="Escribe tu contenido aqui."></textarea>
                            <span style="padding-left: 0px!important;" v-if="errors2.content" class="has-error"
                                  v-text="errors2.content[0]"></span>
                        </div>
                        <span class="up_left_span"><a href="#"
                                                      v-on:click.prevent="back2">VOLVER AL PASO ANTERIOR</a></span>
                        <button v-on:click.prevent="createResourceSetp2" class="resource_2">Subir</button>
                        <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>

                    </div>
                </div>
            </div>
            <div class="login_content create_resourse">
                <div class="step_4">
                    <h2>Crear un Recurso - Paso 3 de 3 <br> <span>Sube tu archivo</span>
                        <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                             alt=""></h2>
                    <div class="login_inner final_step_wrapper clearfix">

                        <div class="final_step">
                            <div class="col-sm-10 col-sm-offset-2">
                                <h3>
                                    <img :src="recurso.catThumb" alt="">
                                    @{{ recurso.title }}
                                </h3>
                                <p v-text="recurso.description"></p>
                            </div>
                        </div>
                        <div class="input_content final_step_input clearfix" :class="{'has-error':errors3.attach}">
                            <div class="col-sm-2">
                                <label class="text-center">ARCHIVO</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="file" name="attach" placeholder="Elije tu archivo"
                                       style="padding: 12px!important;">
                                <span style="margin-top: 0px!important;" v-if="errors3.attach" class="has-error"
                                      v-text="errors3.attach[0]"></span>
                                <p>PDF, DOC, DOCX, PPT, PPTX, RTF, TXT</p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <span class="up_left_span">
                            <a href="#" v-on:click.prevent="back2">VOLVER AL PASO ANTERIOR</a>
                        </span>
                            <button v-on:click.prevent="createResourceSetp3" class="resource_2">Subir</button>
                            <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="sr-overlay"></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/resource_create.js')}}?js={{uniqid()}}"></script>
@else

    <script src="{{ asset('js/app.js') }}"></script>
@endif


@yield('scripts')
<?php $messages = ['success', 'danger', 'warning']; foreach($messages as $msg){?>
@if(session()->has($msg))
    <script type="text/javascript">
        new PNotify({
            title: '{{ucfirst($msg)}} Message',
            text: '{!! session($msg) !!}',
            shadow: true,
            addclass: 'stack_top_right',
            type: '{{$msg}}',
            width: '290px',
            delay: 2000
        });
    </script>
@endif
<?php }?>
<script>
    $(document).ready(function () {
        $('#select2').select2();
    });

    $(document).ready(function () {
        var service_head = $(".service_head").outerHeight();
        var author = $(".author").outerHeight();
        var comment = $(".comment").outerHeight();
        var minheight = service_head + author + comment;
        console.log(minheight);

        var height1 = $(".service_inner > p").map(function () {
                return $(this).height();
            }).get(),

            maxHeight1 = Math.max.apply(null, height1);

        var height2 = $(".service_inner > h4").map(function () {
                return $(this).height();
            }).get(),

            maxHeight2 = Math.max.apply(null, height2);

        maxHeight = maxHeight1 + maxHeight2;

        totalheight = minheight + maxHeight + "px";
        console.log(totalheight);
        $(".service_inner").css("min-height", totalheight);
    });
</script>
</body>
</html>