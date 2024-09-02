<?php
$this->load->view('include/side_menu');
?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?php echo $title; ?></h3>
        <div class="box-tool pull-right"></div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-bordered table-striped" id="example1" width='100%'>
            <thead>
                <tr class='bg-purple'>
                    <th class="text-center">#</th>
                    <th class="text-center">IPP</th>
                    <th class="text-center">Customer Name</th>
                    <th class="text-center">Project Name</th>
                    <th class="text-center">By</th>
                    <th class="text-center">Date</th>
                    <th class="text-center" width='13%'>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $key => $value) {
                    $key++;
                    $detail    = "<a href='".site_url($this->uri->segment(1)). '/view_ipp/' . $value['no_ipp'] . "/detail' class='btn btn-sm btn-warning' title='Detail' data-role='qtip'><i class='fa fa-eye'></i></a>";
                    $release   = "&nbsp;<button class='btn btn-sm btn-success confirm' title='Confirm' data-no_ipp='" . $value['no_ipp'] . "'><i class='fa fa-check'></i></button>";
                    $reject    = "&nbsp;<button class='btn btn-sm btn-danger reject' title='Reject' data-no_ipp='" . $value['no_ipp'] . "'><i class='fa fa-reply'></i></button>";
                    echo "<tr>";
                    echo "<td align='center'>" . $key . "</td>";
                    echo "<td>" . strtoupper($value['no_ipp']) . "</td>";
                    echo "<td>" . strtoupper($value['nm_customer']) . "</td>";
                    echo "<td>" . strtoupper($value['project']) . "</td>";
                    echo "<td>" . strtoupper($value['app_by']) . "</td>";
                    echo "<td>" . date('d-M-Y', strtotime($value['app_date'])) . "</td>";
                    echo "<td  align='center'>" . $detail . $release . $reject . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<?php $this->load->view('include/footer'); ?>
<script>
    $(document).on('click', '.confirm', function() {
        var no_ipp = $(this).data('no_ipp');
        swal({
            title: "Are you sure?",
            text: "Confirm IPP ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Process it!",
            cancelButtonText: "No, cancel process!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                loading_spinner();
                $.ajax({
                    url: base_url + active_controller + '/confirm_ipp/' + no_ipp,
                    type: "POST",
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status == 1) {
                            swal({
                                title: "Save Success!",
                                text: data.pesan,
                                type: "success",
                                timer: 3000
                            });
                            window.location.href = base_url + active_controller + '/confirm';
                        } else if (data.status == 0) {
                            swal({
                                title: "Save Failed!",
                                text: data.pesan,
                                type: "warning",
                                timer: 3000
                            });
                        }
                    },
                    error: function() {
                        swal({
                            title: "Error Message !",
                            text: 'An Error Occured During Process. Please try again..',
                            type: "warning",
                            timer: 3000
                        });
                    }
                });
            } else {
                swal("Cancelled", "Data can be process again :)", "error");
                return false;
            }
        });
    });

    $(document).on('click', '.reject', function() {
        var no_ipp = $(this).data('no_ipp');
        swal({
            title: "Are you sure?",
            text: "Reject IPP ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Process it!",
            cancelButtonText: "No, cancel process!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                loading_spinner();
                $.ajax({
                    url: base_url + active_controller + '/reject_ipp/' + no_ipp,
                    type: "POST",
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status == 1) {
                            swal({
                                title: "Save Success!",
                                text: data.pesan,
                                type: "success",
                                timer: 3000
                            });
                            window.location.href = base_url + active_controller + '/confirm';
                        } else if (data.status == 0) {
                            swal({
                                title: "Save Failed!",
                                text: data.pesan,
                                type: "warning",
                                timer: 3000
                            });
                        }
                    },
                    error: function() {
                        swal({
                            title: "Error Message !",
                            text: 'An Error Occured During Process. Please try again..',
                            type: "warning",
                            timer: 3000
                        });
                    }
                });
            } else {
                swal("Cancelled", "Data can be process again :)", "error");
                return false;
            }
        });
    });
</script>