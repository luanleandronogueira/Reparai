<script src="assets/js/script.js"></script>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

   <script>
      $(document).ready(function() {
         // Inicialização da tabela de entidades (caso exista na página atual)
         if ($('#tabela-entidades').length) {
            $('#tabela-entidades').DataTable({
               "language": {
                  "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
               },
               "pageLength": 10,
               "order": [[0, "asc"]],
               "columnDefs": [{ "orderable": false, "targets": [4, 5] }]
            });
         }
         
         // Inicialização da tabela de imóveis
         if ($('#tabela-imoveis').length) {
            $('#tabela-imoveis').DataTable({
               "language": {
                  "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
               },
               "pageLength": 10,
               "order": [[0, "desc"]]
            });
         }
      });
   </script>
</body>
</html>