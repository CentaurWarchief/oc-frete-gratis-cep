<?php echo $header, $column_left ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-cep" data-toggle="tooltip" title="Salvar" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                </button>
            </div>
            <h1><?php echo $heading_title ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li>
                    <a href="<?php echo $breadcrumb['href']; ?>">
                        <?php echo $breadcrumb['text']; ?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if(isset($error)) { ?>
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-circle"></i>
            <?php echo $error ?>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-pencil"></i>
                    <?php echo $text_edit ?>
                </h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" id="form-cep" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="ceps">
                            <span data-toggle="tooltip" title="<?php echo $help_ceps ?>">
                                <?php echo $entry_ceps ?>
                            </span>
                        </label>
                        <div class="col-sm-6">
                            <textarea name="ceps" id="ceps" class="form-control"><?php echo implode(' ', $cep_ceps) ?></textarea>
                            <?php if (isset($error_postcode)) { ?>
                            <div class="text-danger">
                                <?php echo $error_postcode ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="cep-status">
                            <?php echo $entry_status ?>
                        </label>
                        <div class="col-sm-6">
                            <select name="cep_status" id="cep-status" class="form-control">
                                <?php if ($cep_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled ?></option>
                                <option value="0"><?php echo $text_disabled ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="cep-sort-order"><?php echo $entry_sort_order ?></label>
                        <div class="col-sm-6">
                            <input type="text" name="cep_sort_order" value="<?php echo $cep_sort_order ?>" placeholder="<?php echo $entry_sort_order ?>" id="cep-sort-order" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer ?>