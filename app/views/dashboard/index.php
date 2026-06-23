<script>
document.addEventListener('DOMContentLoaded', async () => {
    const targets = {
        pessoas: document.getElementById('totalPessoas'),
        tipos: document.getElementById('totalTipos'),
        atendimentos: document.getElementById('totalAtendimentos')
    };

    for (const [controller, element] of Object.entries(targets)) {
        try {
            const response = await AtendeLabApi.get(controller, 'listar');
            element.textContent = AtendeLabApi.toList(response).length;
        } catch (error) {
            element.textContent = '!';
            element.title = error.message;
        }
    }
});
</script>
