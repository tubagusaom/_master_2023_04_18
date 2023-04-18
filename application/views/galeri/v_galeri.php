<style type="text/css">
    a {
        text-decoration: none;
    }
    .thumb-info .thumb-info-wrapper img {
        height: 155px;
    }
    @media only screen and (max-width: 600px){
    .single-post {
        margin-left: -60px;
    }
}
    @media only screen and (min-width: 601px) and (max-width: 991px){
    .single-post {
        margin-left: -60px;
    }
    .col-md-4 {
        width: 50%;
    }
}
</style>
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?=base_url()?>">Home</a></li>
                    <li class="active">Gallery</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1> Photo Gallery</h1>
            </div>
        </div>
    </div>
</section>
     <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="blog-posts single-post">

                    <article class="post post-large blog-single-post">
                    <div class="post-content">

                        <div class="post-meta">
                            <span><i class="fa fa-user"></i> By <a href="#">Admin</a> </span>
                            <span><i class="fa fa-tag"></i> <a href="#">Photo Gallery</a></span>
                            
                        </div>
                    <ul class="portfolio-list sort-destination lightbox" data-sort-id="portfolio" data-plugin-options="{'delegate': 'a.lightbox-portfolio', 'type': 'image', 'gallery': {'enabled': true}}">
                            <?php foreach ($value as $key => $value) { ?>
                                <li class="col-md-4  col-xs-12 ">
                                    <div class="portfolio-item">
                                        <span class="thumb-info thumb-info-lighten thumb-info-bottom-info thumb-info-centered-icons">
                                            <span class="thumb-info-wrapper">
                                                <img src="<?php echo base_url().'assets/img/gallery/'.$value->foto ?>" alt="" style="width: 100%">
                                                <span class="thumb-info-title">
                                                    <span class="thumb-info-inner">
                                                    <?php echo $value->keterangan; ?>
                                                    </span>
                                                </span>
                                                <span class="thumb-info-action">
                                                    <a href="<?php echo base_url().'assets/img/gallery/'.$value->foto ?>" class="lightbox-portfolio">
                                                        <span class="thumb-info-action-icon thumb-info-action-icon-light"><i class="fa fa-search-plus"></i></span>
                                                    </a>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </li>
                            <?php } ?>
                    </ul>

                </div>
            </article>

        </div>
    </div>

    <div class="col-md-3">
        <aside class="sidebar">

            <form>
                <div class="input-group input-group-lg">
                    <input class="form-control" placeholder="Search..." name="s" id="s" type="text">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>

            <hr>
            <h4 class="heading-primary">Berita Lainnya</h4>
            <ul class="list list-icons list-primary list-side-borders mt-xlg">
            <?php foreach ($berita_lainnya as $key => $value) { ?>
                <li><i class="fa fa-check"></i> <a href="<?=base_url().'profile/index/'.$value->id?>"><?=$value->judul?></a></li>
               
            <?php } ?>
            </ul>
        </aside>
    </div>
</div>

</div>