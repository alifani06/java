<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script>
        function showPaymentFields() {
            // Sembunyikan semua field pembayaran terlebih dahulu
            const paymentFields = document.querySelectorAll('.payment-field');
            paymentFields.forEach(field => field.hidden = true);
    
            // Dapatkan metode pembayaran yang dipilih
            const metodebayar = document.getElementById('metodebayar').value;
            const subTotalField = document.getElementById('sub_total');
            let subTotal = parseFloat(subTotalField.value);
    
            // Debugging: Tampilkan nilai sub_total di konsol
            console.log('Sub Total:', subTotal);
    
            // Cek apakah subTotal adalah angka valid
            if (isNaN(subTotal)) {
                subTotal = 0;
            }
    
            if (metodebayar === "gobiz") {
                document.getElementById('gobiz-fields').hidden = false;
                const feeField = document.getElementById('gobiz_fee');
                const fee = subTotal * 0.20;
                feeField.value = fee.toFixed(2);
                subTotalField.value = (subTotal + fee).toFixed(2);
            } else if (metodebayar === "mesinedc") {
                document.getElementById('mesinedc-fields').hidden = false;
                const feeField = document.getElementById('struk_edc_fee');
                const fee = subTotal * 0.01;
                feeField.value = fee.toFixed(2);
                subTotalField.value = (subTotal + fee).toFixed(2);
            } else if (metodebayar === "transfer") {
                document.getElementById('transfer-fields').hidden = false;
            } else if (metodebayar === "qris") {
                document.getElementById('qris-fields').hidden = false;
            } else if (metodebayar === "tunai") {
                document.getElementById('tunai-fields').hidden = false;
            } else if (metodebayar === "voucher") {
                document.getElementById('voucher-fields').hidden = false;
            }
        }
    
        // Tambahkan event listener untuk menangani perubahan pada sub_total
        document.getElementById('sub_total').addEventListener('input', showPaymentFields);
    
        // Panggil showPaymentFields saat halaman dimuat
        window.onload = showPaymentFields;
    </script>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col mb-3 d-flex align-items-center">
            <label for="sub_total" class="mr-2">Sub Total</label>
            <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="0" oninput="validateNumberInput(event); calculateSubTotal()">
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <label class="form-label" for="metodebayar">Metode Pembayaran</label>
                <select class="form-control" id="metodebayar" name="metodebayar" onchange="showPaymentFields()">
                    <option value="">- Pilih -</option>
                    <option value="mesinedc">MESIN EDC</option>
                    <option value="gobiz">GO-BIZ</option>
                    <option value="transfer">TRANSFER</option>
                    <option value="qris">QRIS</option>
                    <option value="tunai">TUNAI</option>
                    <option value="voucher">VOUCHER</option>
                </select>
            </div>
        </div>
        <div id="payment-fields">
            <!-- Form untuk GO-BIZ -->
            <div id="gobiz-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="gobiz_code">No GoFood</label>
                    <input type="text" id="gobiz_code" name="gobiz_code" class="form-control" placeholder="Masukkan kode GO-BIZ">
                </div>
                <div class="form-group">
                    <label for="gobiz_fee">Fee (20%)</label>
                    <input type="text" id="gobiz_fee" name="gobiz_fee" class="form-control" placeholder="Masukkan fee" readonly>
                </div>
            </div>
        
            <!-- Form untuk MESIN EDC -->
            <div id="mesinedc-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="struk_edc">No Struk EDC</label>
                    <input type="text" id="struk_edc" name="struk_edc" class="form-control" placeholder="Masukkan No Struk EDC">
                </div>
                <div class="form-group">
                    <label for="struk_edc_fee">Fee (1%)</label>
                    <input type="text" id="struk_edc_fee" name="struk_edc_fee" class="form-control" readonly>
                </div>
            </div>
        
            <!-- Form untuk TRANSFER -->
            <div id="transfer-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="no_rek">No Rekening</label>
                    <input type="text" id="no_rek" name="no_rek" class="form-control">
                </div>
            </div>
        
            <!-- Form untuk QRIS -->
            <div id="qris-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="qris_code">No Referensi</label>
                    <input type="text" id="qris_code" name="qris_code" class="form-control" placeholder="Masukkan kode QRIS">
                </div>
            </div>
        
            <!-- Form untuk TUNAI -->
            <div id="tunai-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="tunai_amount">Jumlah Tunai</label>
                    <input type="number" id="tunai_amount" name="tunai_amount" class="form-control" placeholder="Masukkan jumlah tunai">
                </div>
            </div>
        
            <!-- Form untuk VOUCHER -->
            <div id="voucher-fields" class="payment-field" hidden>
                <div class="form-group">
                    <label for="no_voucher">No Voucher</label>
                    <input type="text" id="no_voucher" name="no_voucher" class="form-control" placeholder="Masukkan no voucher">
                </div>
                <div class="form-group">
                    <label for="nominal_voucher">Nominal Voucher</label>
                    <input type="text" id="nominal_voucher" name="nominal_voucher" class="form-control" placeholder="Masukkan nominal voucher">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
