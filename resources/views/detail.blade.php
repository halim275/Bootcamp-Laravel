@extends('header')

@section('content')
    <section class="roadmap catalog-top d-none d-sm-block py-5">
        <div class="container">


            <div class="row course-categories">
                <div class="col-lg-12 col-12 mb-md-3 mb-0">
                    <h4 class="header-cate ms-2">
                        Bootcamp
                    </h4>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="course-card-responsive">
                        <div class="d-flex align-items-center align-items-md-start flex-md-column gap-md-4 flex-row">

                            <img src="{{ asset('img/' . $memberTransaction->bootcamp->thumbnail) }}" class="thumbnail-course" alt="Intensive Bootcamp Web Development dengan Laravel" />

                            <div class="course-detail">
                                <a href="#" class="course-name line-clamp">
                                    {{ $memberTransaction->bootcamp->title }}
                                </a>


                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8 col-md-10 col-12">
                    <div class="course-card-responsive">
                        <div class="d-flex align-items-center align-items-md-start flex-md-column gap-md-4 flex-row">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        Total Pembayaran
                                    </div>
                                    <div class="col text-end">
                                        Rp {{ number_format($memberTransaction->price, 2, ',', '.') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        PPN(11%)
                                    </div>
                                    <div class="col text-end">
                                        Rp {{ number_format($memberTransaction->ppn, 2, ',', '.') }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        Jumlah yang harus dibayar
                                    </div>
                                    <div class="col text-end">
                                        Rp {{ number_format($memberTransaction->final_price, 2, ',', '.') }}
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col">
                                        Status pembayaran
                                    </div>
                                    <div
                                        class="col @if (ucfirst($memberTransaction->status) == 'PENDING') text-warning
                                    @elseif (ucfirst($memberTransaction->status) == 'EXPIRED')
                                        text-danger
                                        @else
                                        text-success @endif text-end">
                                        <b>{{ ucfirst($memberTransaction->status) }}</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @if (ucfirst($memberTransaction->status) == 'PENDING')
                            <div class="col-lg-12 col-md-12 col-12">
                                <p>Silahkan selesaikan pembayaran sebelum <b>{{ $memberTransaction->transaction_exp }}</b>.
                                    Terima Kasih</p>
                                <a href="{{ $memberTransaction->xendit->invoice_url ?? null }}">
                                    <button type="button" class="btn btn-primary" style="float: right;">Selesaikan
                                        Pembayaran</button>
                                </a>
                            </div>
                        @endif
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
                if ($(this).val() == 'ewallet') {
                    $('#phoneNumber').show();
                } else {
                    $('#phoneNumber').hide();
                }
            });
        });
    </script>
@endsection
