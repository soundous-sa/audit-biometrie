<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>تقرير التدقيق - {{ $audit->id ?? '' }}</title>

    <style>
        /* Page / DomPDF settings */
        @page {
            margin: 30mm 18mm 25mm 18mm; /* top right bottom left */
        }
  /* إضافة خط عربي */
       @font-face {
    font-family: 'Amiri';
    src: url("{{ public_path('fonts/Amiri-Regular.ttf') }}") format('truetype');
    font-weight: normal;
    font-style: normal;
}

        html, body {
            font-family: 'Amiri', 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #000;
            font-size: 14px;
            line-height: 1.8;
        }
        /* Header layout: three columns (right, center, left) */
        .header {
            width: 100%;
            overflow: hidden;
            margin-bottom: 6px;
            position: relative;
        }

        .header .col-right,
        .header .col-left {
            width: 35%;
            display: inline-block;
            vertical-align: top;
        }

        .header .col-center {
            width: 30%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        .header .col-right {
            text-align: right;
            padding-right: 6px;
            font-size: 12px;
        }

        .header .col-left {
            text-align: left;
            padding-left: 6px;
            font-size: 12px;
        }

        /* Logo */
        .logo {
            display: block;
            margin: 0 auto;
            width: 120px;
            height: auto;
        }

        /* Small header text under logo (center) */
        .center-lines {
            font-size: 12px;
            margin-top: 4px;
        }

        /* reference number handwriting style */
        .ref-number {
            position: absolute;
            right: 40%;
            top: 18px;
            font-size: 13px;
        }

        /* Subject line (centered, bold, underlined like the sample) */
        .subject {
            text-align: right;
            font-weight: bold;
            margin: 18px 0;
            font-size: 14px;
        }

       

        /* Main content block */
        .content {
            margin: 0 6mm;
            text-align: justify;
            font-size: 13px;
        }

        .content p {
            margin: 8px 0;
            text-indent: 25px;
        }

        /* List-like bullet points similar to sample (• with indent) */
        .points {
            margin: 10px 0 10px 0;
            padding: 0 10px;
            list-style: disc;
            list-style-position: inside;
        }

        .points li {
            margin: 6px 0;
            padding-right: 6px;
        }

        /* Footer signature block (left side in document) */
        .signature {
            margin-top: 40px;
            width: 100%;
        }

        .signature .left {
            float: left;
            width: 45%;
            text-align: left;
            font-size: 13px;
        }

        .signature .left .name {
            margin-top: 40px;
            font-weight: bold;
            text-align: left;
        }

        /* Attachments / notes bottom right like sample */
        .attachments {
            clear: both;
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
        }

        /* small footer date line (bottom) */
        .doc-date {
            position: fixed;
            bottom: 12mm;
            right: 18mm;
            font-size: 11px;
        }

        /* Keep line-height and spacing similar to scanned doc */
        strong { font-weight: 700; }
    </style>
</head>
<body>
 <div>
    {!! $audit->content_ar !!}
</div>

     <!-- Header  -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0px; font-size: 15px;">
    <tr>
        <!-- Right column: administration lines -->
        <td style="text-align: center; vertical-align: middle; width: 33%;">
            <div>المملكة المغربية</div>
            <div>رئيس الحكومة</div>
            <div>المديرية العامة لإدارة السجون وإعادة الإدماج</div>
            <div>الكتابة العامة</div>
            <div><strong>قسم نظم المعلومات</strong></div>
            <div><strong>---</strong></div>
        </td>

        <!-- Center column: logo -->
        <td style="text-align: center; vertical-align: middle; width: 32%;">
            <img src="{{ public_path('images/Coat_of_arms_of_Morocco.svg') }}" alt="شعار المملكة" style="height: 150px; display: block; margin: 0 auto;">
        </td>

        <!-- Left column: city & date -->
        <td style="text-align: left; vertical-align: middle; width: 28%;">
            <div>{{ $audit->city ?? 'الرباط' }} في :</div>
        </td>
    </tr>
</table>
     <!-- Under header -->
<div style="text-align: center; margin-top: 10px; line-height: 1.6;">
    <p style="margin: 0; font-size: 14px; font-weight: bold;">إلى</p>
    <p style="margin: 0; font-size: 16px; font-weight: bold;">السيد مدير السجن المحلي الوداية</p>
</div>


    <!-- Subject -->
    <div class="subject">
    <p><span style=" text-decoration: underline;">الموضوع:</span> تذكير حول عملية إدراج المعطيات البيومترية بالنظام المعلوماتي الخاص بالسجن المحلي الوداية</p>
    <p><span style=" text-decoration: underline;">المرجع:</span> كتابي عدد :17999/80I/25 بتاريخ 24/04/2024</p>
</div>


    <!-- Salutation -->
    <div class="content">
        <p style="text-indent:0; text-align:center;">
            السلام تام بوجود مولانا الإمام دام له العز والتمكين،
        </p>

        <!-- Main paragraphs (استعمال متغيرات من الموديل audit) -->
        <p>
            وبعد، في إطار تتبع عملية إدراج المعطيات البيومترية بالنظام المعلوماتي ، تبين أن نسبة المعتقلين الذين تم تؤخذ لهم اقل من 10 اصابع  بمؤسستكم يتعدى بكثير  المعدل الوطني, ومن  اجل الوقوف على هده الحالة تم ايفاد عنصر من قسم نضم المعلوميات بتاريخ 2025/06/25 من اجل اخد بصمات هؤلاء المعتقلين وكانت النتيجة كالاتي:
        </p>

        <!-- Points similar to bullets in sample -->
        <ul class="points">
            <li>معتقلين أخذت لهم البصمات لجميع الأصابع (10 أصابع) بنسبة <strong>{{ $audit->stats['full_finger_percent'] ?? '89.47' }}%</strong>.</li>
            <li> معتقلين استقرت وضعية  بصماته.</li>
            <li> معتقلين تمت معاينتهم عينيا دون اللجوء الى   لاخد بصماتهم (بتر, إعاقة,تشوه ...).</li>
        </ul>

        <p>
            و من خلال هذه الارقام، تبين أن بعض الموظفين المكلفين بهذه العملية لم يتقيدوا بالإجراءات المنصوص عليها في بدليل الاستعمال التقنية البيومترية(مدكرة السيد المنوب العام رقم 32 عدد 11068/80I/22 بتاريخ 25/06/2025) ولا يقومون يتحيين دورى لاخد بصمات المعتقلين الدين لم يتم اخد البصمات لجميع اصابعهم.
        </p>

        <p>
             لذا ونظرا لأهمية الموضوع في ظبط هوية المعتقل وحالة العود, ندعوكم لإعطاء تعليماتكم لمصالحكم المختصة قصد ايلاء العناية القصوى لهده العملية و التقيد بالتوجيهات المنضمة بالدليل  والحرص على عدم تعيين موظفين غير مدربين  للقيام بها , وللمزيد من التوجيهاته والارشاد والتكوين يمكن  الاتصال بقسم نظم المعلوميات الدي يبقى رهن الاشارة .
        </p>
    </div>

    <!-- Signature block (left) -->
    <div class="signature">
        <div class="left">
            <div style="text-align:left;">
                والسلام
            </div>
        </div>
    </div>

    <!-- Attachments / notes on bottom right similar to sample -->
    <div class="attachments">
        <div>
            <P style=" text-decoration: underline;">  المرفقات:</P>
        </div>
        <div>— محضر الزيارة الميدانية  للسجن المحلي الاوداية بتاريخ 26/06/2026.</div>
        <div>
            <P style=" text-decoration: underline;">  نسخة قصد الاخبار موجهة الى:</P>
        </div>
         <div>— السيد مدير الضبط القضائى.</div>
        <div>— السيد مدير سلامة السجون والأشخاص والمباني والمنشآت المخصصة للسجون.</div>
    </div>
</body>
</html>
