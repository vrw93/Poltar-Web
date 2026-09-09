<div class="itemPanel" style="min-width: 320px;max-height:fit-content;">
    <h3><i class="fa-solid fa-address-card"></i> Data Diri</h3>
    <p>Nama:</p>
    <input name="nama" type="text" placeholder="Masukkan Nama Panjang" required 
    value="<?=$_POST['nama'] ?? ''?>">
    <p>Nomor Absen:</p>
    <input name="no" type="number" placeholder="Masukkan No Absen" required max="36" min="1"
    value="<?=$_POST['no'] ?? '1'?>">
    <p>Kelas:</p>
    <select name="kelas" required>
        <option value="" disabled hidden <?= empty($_POST['kelas']) ? 'selected' : '' ?>>
            -- Pilih Kelas --
        </option>

        <?php foreach($class as $item): ?>
            <option
                value="<?=$item['id']?>"
                <?= ($_POST['kelas'] ?? '') == $item['id'] ? 'selected' : '' ?>
            >
                <?=$item['name']?>
            </option>
        <?php endforeach; ?>
    </select>
</div>