<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Penghargaan</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background-color: #f5f5f5;
        }

        .certificate {
            width: 297mm;
            /* A4 Landscape width */
            height: 210mm;
            /* A4 Landscape height */
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
            border: 3px solid #3950A2;
            box-shadow: inset 0 0 30px rgba(57, 80, 162, 0.1);
            page-break-after: always;
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            border: 2px solid #3950A2;
            border-right: none;
            border-bottom: none;
        }

        .certificate::after {
            content: '';
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border: 2px solid #3950A2;
            border-left: none;
            border-top: none;
        }

        .certificate-wrapper {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
        }

        .header {
            margin-bottom: 30px;
        }

        .ornament {
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #3950A2, #2d3d7f, #3950A2);
            margin: 0 auto 20px;
        }

        .title {
            font-size: 48pt;
            font-weight: bold;
            color: #3950A2;
            letter-spacing: 3px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 16pt;
            color: #666;
            font-style: italic;
            margin-bottom: 30px;
        }

        .divider {
            width: 80%;
            height: 2px;
            background: #3950A2;
            margin: 25px auto;
        }

        .content {
            margin: 40px 0;
        }

        .intro-text {
            font-size: 14pt;
            color: #555;
            margin-bottom: 20px;
        }

        .name {
            font-size: 44pt;
            font-weight: bold;
            color: #2d3d7f;
            margin: 30px 0;
            letter-spacing: 1px;
        }

        .achievement-text {
            font-size: 13pt;
            color: #555;
            line-height: 1.8;
            margin: 20px 0;
        }

        .event-title {
            font-size: 28pt;
            font-weight: bold;
            color: #3950A2;
            margin: 20px 0;
            font-style: italic;
        }

        .hours-info {
            font-size: 14pt;
            color: #555;
            margin: 20px 0;
        }

        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
            padding-top: 40px;
        }

        .signature-block {
            width: 35%;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 50px;
            font-size: 12pt;
            color: #333;
            font-weight: bold;
        }

        .signature-title {
            font-size: 10pt;
            color: #666;
            margin-top: 5px;
        }

        .footer {
            position: absolute;
            bottom: 30px;
            left: 80px;
            right: 80px;
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            color: #999;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        .certificate-number {
            font-size: 9pt;
            color: #aaa;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="certificate-wrapper">
            <div class="header">
                <div class="ornament"></div>
                <div class="title">Sertifikat Penghargaan</div>
                <div class="ornament"></div>
            </div>

            <div class="divider"></div>

            <div class="content">
                <p class="intro-text">Dengan ini kami persembahkan penghargaan kepada:</p>

                <div class="name">{{ $name }}</div>

                <div class="achievement-text">
                    Atas dedikasi dan kontribusi nyata sebagai relawan dalam kegiatan sosial
                </div>

                <div class="event-title">"{{ $eventTitle }}"</div>

                <div class="achievement-text">
                    Dengan total waktu kontribusi selama <strong>{{ $hours }} jam</strong>
                </div>

                <p class="intro-text" style="margin-top: 40px; font-style: italic; color: #888;">
                    Semoga dedikasi Anda menginspirasi banyak orang untuk berbuat kebaikan
                </p>
            </div>

            <div class="divider"></div>

            <div class="signature-section">
                <div class="signature-block">
                    <p style="font-size: 11pt; color: #555; margin-bottom: 50px;">Dikeluarkan pada:</p>
                    <p style="font-size: 12pt; color: #333; font-weight: bold;">{{ $issueDate }}</p>
                </div>
                <div class="signature-block">
                    <div class="signature-line">_____________________</div>
                    <div class="signature-title">Koordinator Program</div>
                    <div class="signature-title">Volunteer Management System</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-left">
                <p style="font-size: 9pt;">www.volunteer-management.local</p>
            </div>
            <div class="footer-right">
                <p class="certificate-number">No: {{ $certificateNumber }}</p>
            </div>
        </div>
    </div>
</body>

</html>