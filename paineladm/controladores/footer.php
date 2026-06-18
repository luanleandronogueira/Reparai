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

         if ($('#tabela-servicos').length) {
            $('#tabela-servicos').DataTable({
               "language": {
                  "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
               },
               "pageLength": 10,
               "order": [[0, "desc"]]
            });
         }

         if ($('#tabela-prestador-servico').length) {
            $('#tabela-prestador-servico').DataTable({
               "language": {
                  "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
               },
               "pageLength": 10,
               "order": [[0, "desc"]]
            });
         }
      });
   </script>
   <script>
document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('servico_selector');
    const inputServicos = document.getElementById('servicos_prestados');

    selector.addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        
        // Verifica se selecionou uma opção válida
        if (this.value !== "") {
            let currentVal = inputServicos.value.trim();
            
            // Cria um array temporário para evitar a inserção de duplicatas
            let servicesArray = currentVal.length > 0 ? currentVal.split(',').map(s => s.trim()) : [];
            
            if (!servicesArray.includes(selectedText)) {
                // Se o campo estiver vazio, apenas insere. Se já tiver algo, insere com a vírgula.
                if (currentVal === "") {
                    inputServicos.value = selectedText;
                } else {
                    inputServicos.value = currentVal + ", " + selectedText;
                }
            }
            
            // Reseta o dropdown para o placeholder default imediatamente após o clique
            this.selectedIndex = 0;
        }
    });
});
</script>
</body>
</html>