<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 20px;
        }
        .ticket {
            background-color: #f0f9ff;
            border: 2px dashed #4f46e5;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .ticket-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .ticket-detail {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .ticket-detail-label {
            color: #6b7280;
            font-weight: 500;
        }
        .ticket-detail-value {
            color: #1f2937;
            font-weight: bold;
        }
        .order-id {
            background-color: #dbeafe;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .order-id-label {
            color: #0369a1;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .order-id-value {
            color: #0369a1;
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 E-TIKET</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px;">AmikomEventHub</p>
        </div>

        <div class="content">
            <p>Halo <?php echo e($transaction->customer_name); ?>,</p>
            <p>Terima kasih telah melakukan pembelian tiket di AmikomEventHub. E-Tiket Anda sudah siap!</p>

            <div class="order-id">
                <div class="order-id-label">No. Pesanan</div>
                <div class="order-id-value"><?php echo e($transaction->order_id); ?></div>
            </div>

            <div class="ticket">
                <div class="ticket-title"><?php echo e($event->title); ?></div>
                
                <div class="ticket-detail">
                    <span class="ticket-detail-label">📅 Tanggal & Waktu</span>
                    <span class="ticket-detail-value"><?php echo e($event->date->format('d M Y H:i')); ?></span>
                </div>

                <div class="ticket-detail">
                    <span class="ticket-detail-label">📍 Lokasi</span>
                    <span class="ticket-detail-value"><?php echo e($event->location); ?></span>
                </div>

                <div class="ticket-detail">
                    <span class="ticket-detail-label">🏷️ Kategori</span>
                    <span class="ticket-detail-value"><?php echo e($event->category->name); ?></span>
                </div>

                <div class="ticket-detail">
                    <span class="ticket-detail-label">💰 Harga</span>
                    <span class="ticket-detail-value">Rp <?php echo e(number_format($event->price, 0, ',', '.')); ?></span>
                </div>

                <div class="ticket-detail">
                    <span class="ticket-detail-label">👤 Nama Pembeli</span>
                    <span class="ticket-detail-value"><?php echo e($transaction->customer_name); ?></span>
                </div>
            </div>

            <p style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px; color: #92400e;">
                <strong>⚠️ Penting:</strong> Masukkan nomor pesanan (<?php echo e($transaction->order_id); ?>) saat check-in di lokasi acara.
            </p>

            <p style="text-align: center;">
                <a href="<?php echo e(url('/')); ?>" class="button">Kunjungi Website Kami</a>
            </p>

            <p>Jika ada pertanyaan atau kendala, silakan hubungi kami melalui WhatsApp atau email.</p>

            <p style="color: #6b7280; font-size: 12px;">
                Salam hangat,<br>
                <strong>Tim AmikomEventHub</strong>
            </p>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayai kami sebagai platform pembelian tiket Anda.</p>
            <p>© 2024 AmikomEventHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\laragon\www\finalproject_amikomhub\resources\views/mails/eticket.blade.php ENDPATH**/ ?>