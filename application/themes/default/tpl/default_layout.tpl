<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{T_Folder}/css/default.css" />

        <!-- Blueprint Framework -->
        <link rel="stylesheet" href="{T_Folder}/css/screen.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="{T_Folder}/css/typography.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="{T_Folder}/css/print.css" type="text/css" media="print" />
        <link rel="stylesheet" href="{T_Folder}/css/pagination.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="{T_Folder}/css/jquery.qtip.min.css" />
        <!--[if lt IE 8]>
          <link rel="stylesheet" href="{T_Folder}/css/ie.css" type="text/css" media="screen, projection">
        <![endif]-->
        
        <!-- Jquery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="{T_Folder}/js/forums.js"></script>
        <script type="text/javascript" src="{T_Folder}/js/jquery.qtip.min.js"></script>
    
        <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css' />
    
        <title>{meta: site_title}</title>
    </head>
    
    <body>
        <div class="container">
            {header}
            {messages}
            {navigation}
            <hr />
                <div class="span-24 last">
                    {page_content}
                </div>
                <hr />
            <div id="footer" class="span-24 last">
                {footer}
            </div>
        </div>
    </body>
</html>