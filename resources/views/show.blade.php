<div class="card">
    @if($produto)
        <div class="card-header">
            <h3>{{ $produto['description'] }}</h3>
        </div>
        
        <div class="card-body text-center">
            {{-- Exibindo a imagem que vem da API --}}
            <img src="{{ $produto['thumbnail'] }}" 
                 alt="{{ $produto['description'] }}" 
                 style="max-width: 200px; border: 1px solid #ddd; padding: 10px;">

            <ul class="list-group mt-3">
                <li class="list-group-item"><strong>EAN:</strong> {{ $produto['gtin'] }}</li>
                <li class="list-group-item"><strong>Marca:</strong> {{ $produto['brand']['name'] ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>NCM:</strong> {{ $produto['ncm']['code'] ?? 'Não informado' }}</li>
            </ul>
        </div>
    @else
        <div class="alert alert-danger">
            Produto não encontrado ou erro na consulta.
        </div>
    @endif
</div>