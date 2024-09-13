<!DOCTYPE html>
<html>
<head>
    <style>
        .footer {
            text-align: right;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer .page:after {
            content: "Page " counter(page) " of " counter(pages);
        }
    </style>
</head>
<body>
    <div style="text-align: right; font-size: 10px;">
        Page <span class="page"></span> of <span class="topage"></span>
    </div>
    
</body>
</html>
