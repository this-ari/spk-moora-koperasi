<script src="assets/js/jquery.js"></script>
<script src="assets/js/datatables.js"></script>
<script>
$(document).ready(function() {
    if (document.getElementById('tableInputNilai')) {
        new DataTable('#tableInputNilai', {
            pageLength: 100
        });
    }
    new DataTable('.datatable:not(#tableInputNilai), #datatable:not(#tableInputNilai)');
});
</script>
<script src="assets/js/main.js"></script>