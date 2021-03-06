<?php
// require($URLROOT . "/bitrix/header.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax_index.php";
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cr</title>
    <!-- Latest compiled and minified CSS -->
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Talv/x-editable@develop/dist/bootstrap4-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto&display=swap">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/app.css">
</head>

<body>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="p-4 border-bottom bg-light">
                                <h4 class="card-title mb-0">Th??m ch??? ti??u CR</h4>
                            </div>
                            <div class="card-body">
                                <form id="year-selection" method="GET" action="<?php $URLROOT . "ajax_index.php" ?>">
                                    <div class="form-group row">
                                        <label for="year" class="col-xs-2 col-form-label mr-2">N??m</label>
                                        <div class="col-xs-4 mr-2">
                                            <select id="year-selection" class="form-control" id="year" name="year">
                                                <?php for ($i = date('Y') - 1; $i <= date('Y') + 2; $i++) : ?>
                                                    <option value="<?php echo $i ?>" <?php if ($i == date('Y')) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $i ?></option>
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-1 w-40  flex items-center justify-center">
                                            <i class="material-icons">filter_alt</i>
                                            L???c
                                        </button>

                                        <div class="col-xs-4">
                                            <?php if ($currentDepartment && $currentDepartment['title'] === 'TCKT - IT') : ?>
                                                <a data-target="#addSaleModal" class="btn btn-success flex items-center justify-center" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Th??m</span></a>
                                            <?php endif ?>
                                        </div>
                                    </div>

                                </form>

                                <table class="table table-striped" id="table_cr">
                                    <thead>
                                        <tr>
                                            <td>Ch??? Ti??u</td>
                                            <td>Ph??ng ban</td>
                                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                <th><?php echo "Th??ng {$i}"; ?></th>
                                            <?php endfor ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $result) : ?>
                                            <tr>
                                                <td class="not-editable">
                                                    <?php echo data_get($result, '0.name') ?>
                                                </td>
                                                <td class="not-editable">
                                                    <?php
                                                    $i = array_search(data_get($result, '0.department_id'), array_column($departments, 'value'));
                                                    $element = ($i !== false ? $departments[$i] : null);
                                                    echo data_get($element, 'title');
                                                    ?>
                                                </td>
                                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                    <td data-name="<?php echo $i ?>" data-dept-id="<?php echo data_get($result, '0.department_id') ?>" data-pk="<?php echo data_get($result, '0.target_id') ?>">
                                                        <?php
                                                        $key = array_search($i, array_column($result, 'month'));
                                                        if ($key !== false) {
                                                            echo data_get($result, "{$key}.amount", 0);
                                                        } else {
                                                            echo 0;
                                                        }
                                                        ?>
                                                    </td>
                                                <?php endfor ?>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addSaleModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form name="add_cr" method="POST" action="" class="form-horizontal">
                    <div class="modal-header">
                        <h4 class="modal-title">Th??m m???i ch??? ti??u</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label>Ti??u ?????</label>
                            <input type="text" id="title" class="form-control" required name="title" data-live-search="true" style="width: 100%" />
                        </div>


                        <div class="form-group row">
                            <label>N??m</label>
                            <select id="year-selection_create" class="form-control" id="year" name="year" required>
                                <?php for ($i = date('Y') - 1; $i <= date('Y') + 2; $i++) : ?>
                                    <option value="<?php echo $i ?>" <?php if ($i == date('Y')) {
                                                                            echo "selected";
                                                                        } ?>>
                                        <?php echo $i ?></option>
                                <?php endfor ?>
                            </select>
                            <div class="invalid-feedback">
                                Tr?????ng n??y b???t bu???c nh???p!
                            </div>
                        </div>

                        <div class="form-group row">
                            <label>Ph??ng ban</label>

                            <select id="phongban" class="form-control" required name="department_id" data-live-search="true" style="width: 100%">
                                <option value=''>Ch???n 1 ph??ng ban</option>
                                <?php foreach ($departments as $department) : ?>
                                    <option value="<?php echo $department['value'] ?>">
                                        <?php echo $department['title'] ?></option>
                                <?php endforeach ?>

                            </select>
                        </div>

                        <div class="form-group row">
                            <label>Th??ng</label>
                            <div class="form-group">
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="months[]" value="<?php echo $i ?>">
                                        <label class="form-check-label" for="month_<?php echo $i ?>">Th??ng
                                            <?php echo $i ?></label>
                                    </div>
                                <?php endfor ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label>S??? ch??? ti??u</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="add_new_cr" class="btn btn-success" data-dismiss="modal">Th??m</button>
                        <button type="button" id="cancel_add_new_cr" class="btn btn-secondary mr-1" data-dismiss="modal">Hu???</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.10.4/dayjs.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.2/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/Talv/x-editable@develop/dist/bootstrap4-editable/js/bootstrap-editable.min.js">
    </script>
    <script src="https://unpkg.com/bootstrap-table@1.18.2/dist/extensions/editable/bootstrap-table-editable.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(function() {
            $('#table_cr').DataTable({
                responsive: true,
                ordering: false,
            });

            $('#table_cr tbody tr td:not(.not-editable)').editable({
                send: 'always',
                type: 'text',
                url: "<?php echo $URLROOT; ?>/ajax_update.php",
                params: function(params) {
                    var department_id = $(this).attr('data-dept-id');
                    params.year = 2021;
                    params.department_id = department_id;

                    return params;
                },
                validate: function(value) {
                    if (!Number.isInteger(parseFloat(value))) {
                        return 'Ch??? nh???p s??? nguy??n';
                    }
                },
                success: function(response, newValue) {
                    if (response && response.success) {
                        toastr.success("C???p nh???t th??nh c??ng!");
                    } else {
                        toastr.error("C???p nh???t kh??ng th??nh c??ng!");
                    }
                },
                ajaxOptions: {
                    type: 'POST',
                    dataType: 'json',
                }
            });

            $('#phongban').select2({
                placeholder: 'Ch???n b??? ph???n',
            });
            $('#add_new_cr').on('click', function(e) {
                e.preventDefault();

                //Fetch form to apply custom Bootstrap validation
                var form = $("form[name=add_cr]");

                if (form[0].checkValidity() === false) {
                    e.stopPropagation()
                }

                form.addClass('was-validated');

                var data = form.serialize();

                if (form[0].checkValidity()) {
                    var link = window.location.origin + '/ajax_create.php';
                    $.ajax({
                        url: link,
                        data: data,
                        type: 'POST',
                        success: function(response) {
                            var response = JSON.parse(response);
                            if (response.success) {
                                toastr.success("C???p nh???t th??nh c??ng!");
                                $("form[name=add_cr]").trigger("reset");
                                location.reload();
                            } else {
                                toastr.error("C???p nh???t kh??ng th??nh c??ng!");
                            }

                        }
                    });
                }

            })

            $('#cancel_add_new_cr').on('click', function(e) {
                e.preventDefault();
                $("form[name=add_cr]").trigger("reset");
            })
        });
    </script>
</body>

</html>

<!-- <?php require($URLROOT . "/bitrix/footer.php"); ?> -->