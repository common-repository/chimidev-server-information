<!-- STYLES -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/css/style.css">

<!-- SCRIPTS -->
<!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo THEMEPATH; ?>/js/directorylister.js"></script>

<div id="page-navbar" class="navbar navbar-default">
    <div class="container">

        <?php $breadcrumbs = $lister->listBreadcrumbs(); ?>

        <p class="navbar-text">
            <?php foreach($breadcrumbs as $breadcrumb): ?>
                <?php if ($breadcrumb != end($breadcrumbs)): ?>
                        <a href="<?php echo esc_html($breadcrumb['link']); ?>"><?php echo esc_attr($breadcrumb['text']); ?></a>
                        <span class="divider">/</span>
                <?php else: ?>
                    <?php echo esc_attr($breadcrumb['text']); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </p>

        <div class="navbar-right">

            <ul id="page-top-nav" class="nav navbar-nav">
                <li>
                    <a href="javascript:void(0)" id="page-top-link">
                        <i class="fa fa-arrow-circle-up fa-lg"></i>
                    </a>
                </li>
            </ul>

            <?php  if ($lister->isZipEnabled()): ?>
                <ul id="page-top-download-all" class="nav navbar-nav">
                    <li>
                        <a href="?chimidev_si_zip=<?php echo esc_attr($lister->getDirectoryPath()); ?>" id="download-all-link">
                            <i class="fa fa-download fa-lg"></i>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>

        </div>

    </div>
</div>

<div id="page-content" class="container">

    <?php if($lister->getSystemMessages()): ?>
        <?php foreach ($lister->getSystemMessages() as $message): ?>
            <div class="alert alert-<?php echo $message['type']; ?>">
                <?php echo $message['text']; ?>
                <a class="close" data-dismiss="alert" href="#">&times;</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div id="directory-list-header">
        <div class="row">
            <div class="col-md-7 col-sm-6 col-xs-10">File</div>
            <div class="col-md-2 col-sm-2 col-xs-2 text-right">Size</div>
            <div class="col-md-3 col-sm-4 hidden-xs text-right">Last Modified</div>
        </div>
    </div>

    <ul id="directory-listing" class="nav nav-pills nav-stacked">

        <?php foreach($dirArray as $name => $fileInfo): ?>
            <li data-name="<?php echo esc_html($name); ?>" data-href="<?php echo esc_html($fileInfo['url_path']); ?>">
                <a href="<?php echo esc_html($fileInfo['url_path']); ?>" class="clearfix" data-name="<?php echo esc_html($name); ?>">

                    <div class="row">
                        <span class="file-name col-md-7 col-sm-6 col-xs-9">
                            <i class="fa <?php echo esc_attr($fileInfo['icon_class']); ?> fa-fw"></i>
                            <?php echo $name; ?>
                        </span>

                        <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                            <?php echo esc_attr($fileInfo['file_size']); ?>
                        </span>

                        <span class="file-modified col-md-3 col-sm-4 hidden-xs text-right">
                            <?php echo esc_attr($fileInfo['mod_time']); ?>
                        </span>
                    </div>

                </a>

                <?php if (is_file($fileInfo['file_path'])): ?>

<!--                    <a href="javascript:void(0)" class="file-info-button">-->
<!--                        <i class="fa fa-info-circle"></i>-->
<!--                    </a>-->

                <?php endif; ?>

            </li>
        <?php endforeach; ?>

    </ul>
</div>

<hr>

<div class="footer">
    Powered by <a href="http://www.directorylister.com">Directory Lister</a>
</div>


<div id="file-info-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{modal_header}}</h4>
            </div>

            <div class="modal-body">

                <table id="file-info" class="table table-bordered">
                    <tbody>

                        <tr>
                            <td class="table-title">MD5</td>
                            <td class="md5-hash">{{md5_sum}}</td>
                        </tr>

                        <tr>
                            <td class="table-title">SHA1</td>
                            <td class="sha1-hash">{{sha1_sum}}</td>
                        </tr>

                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>
