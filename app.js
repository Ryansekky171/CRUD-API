// Função para carregar a lista de viagens
function carregarListaViagens() {
    axios.get('http://localhost/CRUD-API/api.php?action=list')
        .then(response => {
            const listaViagens = document.getElementById('listaViagens');
            listaViagens.innerHTML = '';

            response.data.forEach(viagem => {
                const li = document.createElement('li');
                li.innerHTML = `<strong>${viagem.destino}</strong> - ${viagem.descricao} - Partida: ${viagem.data_partida} Retorno: ${viagem.data_retorno}
                <button onclick="carregarDetalhesViagem(${viagem.id})">Detalhes</button>
                <button onclick="editarViagem(${viagem.id})">Editar</button>
                <button onclick="removerViagem(${viagem.id})">Remover</button>`;
                listaViagens.appendChild(li);
            });
        })
        .catch(error => console.error(error));
}

// Função para carregar os detalhes de uma viagem
function carregarDetalhesViagem(id) {
    axios.get(`'http://localhost/CRUD-API/api.php?action=one&id=${id}`)
        .then(response => {
            alert(JSON.stringify(response.data));
        })
        .catch(error => console.error(error));
}

// Função para criar uma nova viagem
function criarViagem() {
    const destino = document.getElementById('destino').value;
    const descricao = document.getElementById('descricao').value;
    const data_partida = document.getElementById('data_partida').value;
    const data_retorno = document.getElementById('data_retorno').value;

    axios.post('http://localhost/CRUD-API/api.php?action=new', {
        destino: destino,
        descricao: descricao,
        data_partida: data_partida,
        data_retorno: data_retorno
    })
    .then(response => {
        alert(response.data);
        carregarListaViagens();
    })
    .catch(error => console.error(error));
}

// Função para editar uma viagem
function editarViagem(id) {
    // Implemente a lógica de edição, se necessário
    alert(`Editar viagem com ID ${id}`);
}

// Função para remover uma viagem
function removerViagem(id) {
    if (confirm("Tem certeza que deseja remover esta viagem?")) {
        axios.post('http://localhost/CRUD-API/api.php?action=delete', { id: id })
            .then(response => {
                alert(response.data);
                carregarListaViagens();
            })
            .catch(error => console.error(error));
    }
}

// Carregar a lista de viagens ao carregar a página
carregarListaViagens();
