@extends('header')

@section('content')
    <section class="roadmap catalog-top d-none d-sm-block py-5">
        <div class="container">


            <div class="row course-categories">
                <div class="col-lg-12 col-12 mb-md-3 mb-0">
                    <h4 class="header-cate ms-2">
                        Katalog Bootcamp
                    </h4>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="course-card-responsive">
                        <div class="d-flex align-items-center align-items-md-start flex-md-column gap-md-4 flex-row">

                            <img src="{{ asset('img/' . $bootcamp->thumbnail) }}" class="thumbnail-course" alt="Intensive Bootcamp Web Development dengan Laravel" />

                            <div class="course-detail">
                                <a href="#" class="course-name line-clamp">
                                    {{ $bootcamp->title }}
                                </a>


                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8 col-md-10 col-12">
                    <div class="course-card-responsive">
                        <form action="{{ route('actCheckout', $bootcamp->id) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center align-items-md-start flex-md-column gap-md-4 flex-row">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            Total Pembayaran
                                        </div>
                                        <div class="col text-end">
                                            Rp {{ number_format($bootcamp->price, 2, ',', '.') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            PPN(11%)
                                        </div>
                                        <div class="col text-end">
                                            Rp {{ number_format($bootcamp->ppn, 2, ',', '.') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            <b>Jumlah yang harus dibayar</b>
                                        </div>
                                        <div class="col text-end">
                                            <b>
                                                Rp {{ number_format($bootcamp->total, 2, ',', '.') }}
                                            </b>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-12 col-md-12 col-12 mt-5">
                                <button type="submit" class="btn btn-primary" style="float: right;">Pilih Metode
                                    Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalTrailerCourse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center gap-3 text-black">
                        <h6 class="modal-title" id="staticBackdropLabel">
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="checkScroll(false)"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="embed-responsive embed-responsive-16by9 video-iframe">
                            <div class="plyr__video-embed" id="player">
                                <iframe allowfullscreen allowtransparency allow="autoplay" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(function() {
            $('#ewallet').change(function() {
                if ($(this).val() != 'va') {
                    $('#phoneNumber').show();
                } else {
                    $('#phoneNumber').hide();
                }
            });
        });
    </script>
@endsection
