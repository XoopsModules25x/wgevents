    <div class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary wge-navbar">
        <div class="container">
            <div class="col-xs-12 col-sm-2 col-lg-1"
                <a href="<{$xoops_url}>" class="navbar-brand xlogo" title="<{$xoops_sitename}>">
                    <img class="wge-nav-logo img-fluid" src="<{$xoops_imageurl}>images/logo.png" alt="<{$xoops_sitename}>">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>
            </div>
            <div class="col-hidden-xs col-sm-10 col-lg-11 collapse navbar-collapse wge-navbar-items" id="navbarResponsive">
                <ul class="navbar-nav float-right">
                    <li class="nav-item">
                        <a class="nav-link wge-navbar-link" href="<{$xoops_url}>"><span class="fa fa-home wge-icon-1"></span><{$smarty.const.THEME_HOME}></a>
                    </li>
                    <!-- begin custom menus - customize these for your system -->
                    <li class="nav-item">
                        <a class="nav-link wge-navbar-link" href="<{$xoops_url}>/modules/wgevents/event.php"><span class="fa fa-calendar wge-icon-1"></span><{$smarty.const.THEME_MODULE5}></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link wge-navbar-link dropdown-toggle" data-toggle="dropdown" href="#" id="xswatch-help-menu">
                            <span class="fa fa-info-circle wge-icon-1"></span><{$smarty.const.THEME_MODULE7}>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="xswatch-help-menu">
                            <a class="dropdown-item"" href="<{$xoops_url}>/uploads/files/Bedienungsanleitung_Teilnehmer_Ferienprogramm_Hochburg_Ach.pdf" target="_blank"><span class="fa fa-info-circle wge-icon-1"></span><{$smarty.const.THEME_MODULE8}></a>
                            <a class="dropdown-item"" href="<{$xoops_url}>/uploads/files/Bedienungsanleitung_Veranstalter_Ferienprogramm_Hochburg_Ach.pdf" target="_blank"><span class="fa fa-info-circle wge-icon-1"></span><{$smarty.const.THEME_MODULE9}></a>
                        </div>
                    </li>
                    <!-- end custom menus -->
                    <{xoInboxCount assign='unread_count'}>
                    <li class="nav-item dropdown">
                        <a class="nav-link wge-navbar-link dropdown-toggle" data-toggle="dropdown" href="#" id="xswatch-account-menu">
                            <span class="fa fa-user wge-icon-1"></span>
                            <{if $xoops_isuser|default:false}>
                                <{if $xoops_name|default:''}><{$xoops_name}><{else}><{$xoops_uname}><{/if}>
                            <{else}>
                                <{$smarty.const.THEME_ACCOUNT}>
                            <{/if}>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="xswatch-account-menu">
                            <{if $xoops_isuser|default:false}>
                                <{if $xoops_isadmin|default:false}>
                                <a class="dropdown-item" href="<{$xoops_url}>/admin.php"><span class="fa fa-wrench wge-icon-1"></span><{$smarty.const.THEME_ACCOUNT_ADMIN}></a>
                                <{/if}>
                                <a class="dropdown-item" href="<{$xoops_url}>/user.php"><span class="fa fa-user wge-icon-1"></span><{$smarty.const.THEME_ACCOUNT_EDIT}></a>
                                <{*
                                <a class="dropdown-item" href="<{$xoops_url}>/viewpmsg.php"><{$smarty.const.THEME_ACCOUNT_MESSAGES}> <span class="badge badge-primary badge-pill"><{xoInboxCount}></span></a>
                                <a class="dropdown-item" href="<{$xoops_url}>/notifications.php"><{$smarty.const.THEME_ACCOUNT_NOTIFICATIONS}></a>
                                *}>
                                <a class="dropdown-item" href="<{$xoops_url}>/user.php?op=logout"><span class="fa fa-sign-out wge-icon-1"></span><{$smarty.const.THEME_ACCOUNT_LOGOUT}></a>
                            <{else}>
                                <a class="dropdown-item" href="<{$xoops_url}>/user.php"><span class="fa fa-user wge-icon-1"></span><{$smarty.const.THEME_ACCOUNT_LOGIN}></a>
                                <a class="dropdown-item" href="<{$xoops_url}>/register.php"><span class="fa fa-sign-in wge-icon-1"></span><{$smarty.const.THEME_ACCOUNT_REGISTER}></a>
                            <{/if}>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
