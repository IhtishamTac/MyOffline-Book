@foreach ($books as $book)
                    {{-- Ini Awal Modal --}}
                    <div class="modal fade" id="modalKeranjang_{{ $book->id }}" style="margin-top: 300px;">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-body">
                                    <div class="d-flex justify-content-between">
                                        <h3>{{ $book->judul_buku }}</h3>
                                        <p style="margin-top: 5px;">Stok : <span
                                                style="color: red; font-weight: 500;">{{ $book->stok }}</span></p>
                                    </div>
                                    <p
                                        style="white-space: nowrap; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $book->deskripsi }}</p>
                                    <form action="{{ route('post-keranjang', $book->id) }}" method="POST">
                                        @csrf
                                        <div class="d-flex justify-content-between mt-2 mb-2">
                                            <p style="margin-top: 4px;">Jumlah</p>
                                            <input type="number" value="1" min="1" name="qty"
                                                style="height: 35px;" class="form-control w-50">
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-success" style="border-radius: 2px;">
                                                Masukan keranjang
                                            </button>
                                            <h5 style="margin-top: 4px;">Rp.
                                                {{ number_format($book->harga_buku, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary w-50" style="border-radius: 2px;"
                                        data-bs-dismiss="modal">Batalkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ini Akhir modal  --}}
                    <div class="col-2 mt-3">
                        <a href="" data-bs-toggle="modal" data-bs-target="#modalKeranjang_{{ $book->id }}">
                            <img src="{{ asset($book->sampul_buku) }}" width="100px" height="290px"
                                style="object-fit: cover;" alt="sampul" class="card-img-top">
                            <p class="btn btn-success w-100" style="border-radius: 2px;">Pilih</p>
                        </a>
                    </div>
                @endforeach