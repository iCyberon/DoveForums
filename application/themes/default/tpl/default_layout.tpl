<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8"/>

        <!-- Blueprint Framework -->
        <link rel="stylesheet" href="{T_Folder}/css/screen.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="{T_Folder}/css/typography.css" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="{T_Folder}/css/print.css" type="text/css" media="print" />
        <link rel="stylesheet" href="{T_Folder}/css/default.css" />
        <link rel="stylesheet" href="{T_Folder}/css/menus.css" />
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
    {header}
        <div class="container">
            
            {messages}
            <div class="span-24 last">
                {navigation}
            </div>
                <div class="span-24 last content">
                    {page_content}
                </div>
            <footer class="span-24 last">
                {footer}
            </footer>
        </div>
    </body>
</html>