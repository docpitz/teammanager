@push('css')
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap-duallistbox.min.css">
@endpush

@push('js')
    <script src="../../js/jquery.bootstrap-duallistbox.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var duallistbox = $('select[name="{{$name_of_select_multiple}}[]"]').bootstrapDualListbox(
                {
                    infoText: '{0} Teammitglied(er)',
                    filterTextClear: 'Anzeige aller',
                    filterPlaceHolder: 'Suche',
                    infoTextFiltered: '<span class="label label-warning">Anzeige</span> {0} von {1}',
                    infoTextEmpty: 'Leere Liste'
                }
            );
        });
    </script>
@endpush
