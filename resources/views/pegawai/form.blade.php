<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" data-toggle="validator">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="nama" id="nama" class="form-control" required autofocus
                                oninvalid="this.setCustomValidity('Nama harus diisi.')"
                                oninput="this.setCustomValidity('')">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jabatan" class="col-lg-2 col-lg-offset-1 control-label">Jabatan</label>
                        <div class="col-lg-6">
                            <select name="jabatan" id="jabatan" class="form-control" required
                                oninvalid="this.setCustomValidity('Silahkan pilih jabatan.')"
                                oninput="this.setCustomValidity('')">
                                <option value="" disabled selected>-- Pilih Jabatan --</option>
                                <option value="Kepala Gudang">Kepala Gudang</option>
                                <option value="Operator Gorengan">Operator Gorengan</option>
                                <option value="Operator Produksi">Operator Produksi</option>
                                <option value="Kasir">Kasir</option>
                                <option value="Supir">Supir</option>
                                <option value="Owner">Owner</option>
                                <option value="Operator Pengiriman">Operator Pengiriman</option>
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telepon" class="col-lg-2 col-lg-offset-1 control-label">Nomor Telepon</label>
                        <div class="col-lg-6">
                            <input type="number" name="telepon" id="telepon" class="form-control" required
                                oninvalid="this.setCustomValidity('Nomor telepon wajib diisi dan hanya boleh angka.')"
                                oninput="this.setCustomValidity('')">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-lg-2 col-lg-offset-1 control-label">Alamat</label>
                        <div class="col-lg-6">
                            <textarea name="alamat" id="alamat" rows="3" class="form-control"></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>