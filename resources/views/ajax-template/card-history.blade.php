@foreach ($transaksi as $item)
                @php
                    $jumlahSemua = 0;
                    foreach ($item->detailtransaksi as $dtl) {
                        $jumlahSemua += $dtl->qty;
                    }
                @endphp
                <div class="col-6 mt-3">
                    <a href="{{ route('detail-history', $item->id) }}" class="card-history">
                        <div class="card">
                            <div class="d-flex">
                                <img src="{{ asset($item->detailtransaksi->first()->book->sampul_buku) }}"
                                    style="width: 130px; height: 210px; object-fit: cover;" alt=""
                                    class="card-img-top">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p style="font-size: large">{{ $item->nama_pembeli }} <span
                                                style="font-weight: 500">({{ $item->created_at }})</span></p>
                                        <p style="padding: 7px; border-radius: 2px; position: absolute; right: 0; top: 0;"
                                            class="text-white bg-success">{{ $item->invoice }} </p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Jumlah Semua Barang : </p>
                                        <p style="font-weight: 500;">{{ $jumlahSemua }}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Total Harga : </p>
                                        <p style="font-weight: 500;">Rp.
                                            {{ number_format($item->total_semua, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p>Uang Dibayarkan : </p>
                                        <p style="font-weight: 500;">Rp.
                                            {{ number_format($item->uang_bayar, 2, ',', '.') }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach