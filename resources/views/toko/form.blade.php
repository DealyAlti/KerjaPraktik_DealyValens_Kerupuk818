<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
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
                        <label for="nama_toko" class="col-lg-2 col-lg-offset-1 control-label">Nama Toko</label>
                        <div class="col-lg-6">
                            <input type="text" name="nama_toko" id="nama_toko" class="form-control" required autofocus
                                oninvalid="this.setCustomValidity('Nama toko harus diisi.')"
                                oninput="this.setCustomValidity('')">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-lg-2 col-lg-offset-1 control-label">Alamat</label>
                        <div class="col-lg-6">
                            <textarea name="alamat" id="alamat" rows="3" class="form-control" required
                                oninvalid="this.setCustomValidity('Alamat harus diisi.')"
                                oninput="this.setCustomValidity('')"></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nomor_telepon" class="col-lg-2 col-lg-offset-1 control-label">Nomor Telepon</label>
                        <div class="col-lg-6">
                            <input type="number" name="nomor_telepon" id="nomor_telepon" class="form-control" required
                                oninvalid="this.setCustomValidity('Nomor telepon wajib diisi dan hanya boleh angka.')"
                                oninput="this.setCustomValidity('')">
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