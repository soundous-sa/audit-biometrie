<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <title>تقرير التدقيق - {{ $audit->id ?? '' }}</title>

    <style>
        /* Page / DomPDF settings */
        @page {
            margin: 30mm 18mm 25mm 18mm;
            /* top right bottom left */
        }

        /* إضافة خط عربي 
        @font-face {
            font-family: 'Amiri';
            src: url("{{ public_path('fonts/Amiri-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }*/

        html,
        body {
            font-family: 'amiri', 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #000;
            font-size: 12px;
            line-height: 1.8;
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
            margin-top: 20px;
            width: 100%;
        }

        .signature .left {
            float: left;
            width: 45%;
            text-align: left;
            font-size: 14px;
        }

        .signature .left .name {
            margin-top: 40px;
            font-weight: bold;
            text-align: center;
        }

        /* Attachments / notes bottom right like sample */
        .attachments {
            clear: both;
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            line-height: 1.4;
        }

        /* small footer date line (bottom) */
        .doc-date {
            position: fixed;
            bottom: 12mm;
            right: 18mm;
            font-size: 11px;
        }

        /* Keep line-height and spacing similar to scanned doc */
        strong {
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div>
        {!! $audit->content_ar !!}
    </div>

    <!-- Header -->
    <table style="width: 100%; border-collapse: collapse; margin:0; padding:0; border:none;">
        <tr>
            <!-- Right column: administration lines -->
            <td style="text-align: center; width: 33%; padding: 0; vertical-align: top;">
                <div>المملكة المغربية</div>
                <div>رئيس الحكومة</div>
                <div>المديرية العامة لإدارة السجون وإعادة الإدماج</div>
                <div>الكتابة العامة</div>
                <div><strong>قسم نظم المعلومات</strong></div>
                <div><strong>---</strong></div>
            </td>

            <!-- Center column: logo -->
            <td style="text-align: center; width: 32%; padding: 0; vertical-align: top;">
                <img src="{{ public_path('images/Coat_of_arms_of_Morocco.svg') }}"
                    alt="شعار المملكة"
                    style="height: 90px; margin:0; padding:0;">
            </td>

            <!-- Left column: city & date -->
            <td style="text-align:center; width: 32%; padding: 0; vertical-align:top;">
                <div style="font-size: 12px;">الرباط في :</div>
            </td>
        </tr>
    </table>


    <!-- Under header -->
    <div style="text-align: center; margin-top: 10px; line-height: 1.6;">
        <p style="margin: 0; font-size: 14px; font-weight: bold;">إلى</p>
        <p style="margin: 0; font-size: 16px; font-weight: bold;"> السيد مدير {{ $etablissement }}</p>
    </div>


    <!-- Subject -->
    <div class="subject">
        <p><span style=" text-decoration: underline;">الموضوع:</span> تذكير حول عملية إدراج المعطيات البيومترية بالنظام المعلوماتي الخاص ب{{ $etablissement }}</p>
        <p><span style=" text-decoration: underline;">المرجع:</span> كتابي عدد :17999/80I/25 بتاريخ 24/04/2024</p>
    </div>


    <!-- Salutation -->
    <div class="content">
        <p style="text-indent:0; text-align:center;">
            سلام تام بوجود مولانا الإمام دام له العز والتمكين،
        </p>

        <!-- Main paragraphs (استعمال متغيرات من الموديل audit) -->
        <p>
            وبعد، في إطار تتبع عملية إدراج المعطيات البيومترية بالنظام المعلوماتي ، تبين أن نسبة المعتقلين الذين تم تؤخذ لهم اقل من 10 اصابع بمؤسستكم يتعدى بكثير المعدل الوطني, ومن اجل الوقوف على هده الحالة تم ايفاد عنصر من قسم نضم المعلوميات بتاريخ {{ $dateAudit }} من اجل اخد بصمات هؤلاء المعتقلين وكانت النتيجة كالاتي:
        </p>

        <!-- Points similar to bullets in sample -->
        <ul class="points">
            <li>
                {{ $audit->nb_verified_fingerprints }} معتقلين أخذت لهم البصمات لجميع الأصابع (10 أصابع) بنسبة
                <strong>{{ number_format($fullFingerPercent, 2) }}%</strong>.
            </li>
            @if($audit->nb_edited_fingerprints > 0)
            <li>{{ $audit->nb_edited_fingerprints }} معتقلين ارتفع عدد الاصابع الماخودة لبصماتهم عن السابق بالرغم من انه لم يصل الى 10 اصابع.</li>
            @endif
            @if($settled > 0)
            <li>{{ $settled }} معتقلين استقرت وضعية بصماتهم.</li>
            @endif
            @if($audit->nb_without_fingerprints > 0)
            <li>{{ $audit->nb_without_fingerprints }} معتقلين تمت معاينتهم عينيا دون اللجوء الى لاخد بصماتهم (بتر, إعاقة,تشوه ...).</li>
            @endif
        </ul>
        @if(optional($audit->responseType)->name == 'لا يتقيدون')
        <p>
            و من خلال هذه الارقام، تبين أن بعض الموظفين المكلفين بهذه العملية لم يتقيدوا بالإجراءات المنصوص عليها في بدليل الاستعمال التقنية البيومترية(مدكرة السيد المنوب العام رقم 32 عدد 11068/80I/22 بتاريخ 29/03/2022) ولا يقومون يتحيين دورى لاخد بصمات المعتقلين الدين لم يتم اخد البصمات لجميع اصابعهم.
        </p>
        <p>
            لذا ونظرا لأهمية الموضوع في ظبط هوية المعتقل وحالة العود, ندعوكم لإعطاء تعليماتكم لمصالحكم المختصة قصد ايلاء العناية القصوى لهده العملية و التقيد بالتوجيهات المنضمة بالدليل والحرص على عدم تعيين موظفين غير مدربين للقيام بها , وللمزيد من التوجيهاته والارشاد والتكوين يمكن الاتصال بقسم نظم المعلوميات الدي يبقى رهن الاشارة .
        </p>
        @elseif(optional($audit->responseType)->name == 'ننوه')
        <p>
            ومن خلال ما سبق ننوه بالعمل الدي يقوم به الموضفون المكلفون بهده العملية ,وحرصكم على التطبيق السليم للاجراءات المضمنة بدليل استعمال التقنية البيومترية(مدكرة السيد المنوب العام رقم 32 عدد 11068/80I/22 بتاريخ 29/03/2022)
        </p>
        <p>
            وللاشارة,يمكنهم الاتصال بقسم نظم المعلوميات الدي يبقى رهن اشارتهم عند الاقتضاء.
        </p>
        @elseif(optional($audit->responseType)->name == 'بدل جهد')
        <p>
            من خلال ما سبق يتعين عليكم اعطاء تعليماتكم للمظفين المكلفين بهده العملية
            من اجل بدل جهد اظافي حتى يتسنى لهم تطبيق الاجراءات النضمنة بدليل استعمال التقنية البيومترية
            (مدكرة السيد المنوب العام رقم 32 عدد 11068/80I/22 بتاريخ 29/03/2022),
            وحثهم على القيام بتحيين دوري(مرة في الاسبوع) لاخد بصمات المعتقلين الدين لم يتم اخد البصمات لجميع اصابعهم.
        </p>
        <p>
            لذا ونظرا لأهمية الموضوع في ظبط هوية المعتقل وحالة العود, ندعوكم لإعطاء تعليماتكم لمصالحكم المختصة قصد ايلاء العناية القصوى لهده العملية و التقيد بالتوجيهات المنضمة بالدليل والحرص على عدم تعيين موظفين غير مدربين للقيام بها , وللمزيد من التوجيهاته والارشاد والتكوين يمكن الاتصال بقسم نظم المعلوميات الدي يبقى رهن الاشارة .
        </p>

        @endif
    </div>

    <!-- Signature block (left) -->
    <div class="signature">
        <div class="left">
            <div style="text-align:left; margin-left:20px;">
                والسلام
            </div>
        </div>
    </div>

    <!-- Attachments / notes on bottom right similar to sample -->
    <div class="attachments">
        <div>
            <P style=" text-decoration: underline;"> المرفقات:</P>
        </div>
        <div>— محضر الزيارة الميدانية {{ $etablissement }} بتاريخ {{ $dateAudit }}.</div>
        <div>
            <P style=" text-decoration: underline;"> نسخة قصد الاخبار موجهة الى:</P>
        </div>
        <div>— السيد مدير الضبط القضائى.</div>
        <div>— السيد مدير سلامة السجون والأشخاص والمباني والمنشآت المخصصة للسجون.</div>
    </div>
</body>

</html>