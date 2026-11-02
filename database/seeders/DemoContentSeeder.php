<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Blog\Models\Category as BlogCategory;
use Modules\Blog\Models\Post;
use Modules\Catalog\Models\Category as CatalogCategory;
use Modules\Catalog\Models\Product;
use Modules\Content\Models\Client;
use Modules\Content\Models\Faq;
use Modules\Content\Models\Testimonial;
use Modules\Media\Models\Media;
use Modules\Page\Enums\PageStatus;
use Modules\Page\Models\Page;
use Modules\Settings\Services\SettingsService;
use Modules\Store\Enums\StoreKind;
use Modules\Store\Models\Store;

final class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->copyAssets();

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@termoflux.com.br'],
            [
                'name' => 'Admin Termoflux',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        $mediaUrl = fn (string $file): string => '/storage/demo/'.$file;
        $now = now();

        app(SettingsService::class)->update([
            'site_name' => 'Termoflux',
            'tagline' => 'Resistências elétricas industriais sob medida.',
            'email' => 'contato@termoflux.com.br',
            'phone' => '(54) 3211-4480',
            'whatsapp' => '54999887766',
            'address' => 'Rua das Indústrias, 1420 — Distrito Industrial, Caxias do Sul/RS',
            'hours' => 'Segunda a sexta, 7h30 às 17h30',
            'facebook' => 'https://www.facebook.com/termoflux',
            'instagram' => 'https://www.instagram.com/termoflux',
            'linkedin' => 'https://www.linkedin.com/company/termoflux',
            'stat_years' => '24',
            'stat_clients' => '380',
            'stat_companies' => '1.200',
            'cookie_text' => 'Usamos cookies para lembrar suas preferências e entender como o site é utilizado. Você pode aceitar todos ou manter apenas os essenciais.',
        ]);

        // ---------------------------------------------------------------- media

        $mediaFiles = [
            'resistencia-cartucho.svg' => ['Resistência cartucho', 800, 800],
            'resistencia-tubular.svg' => ['Resistência tubular em U', 800, 800],
            'resistencia-aletada.svg' => ['Resistência tubular aletada', 800, 800],
            'resistencia-imersao.svg' => ['Resistência de imersão flangeada', 800, 800],
            'termopar.svg' => ['Termopar tipo K', 800, 800],
            'painel.svg' => ['Controlador de temperatura', 800, 800],
            'fabrica.svg' => ['Linha de montagem', 1200, 675],
            'capa-1.svg' => ['Corte de resistência', 1200, 900],
            'capa-2.svg' => ['Curva de aquecimento', 1200, 900],
            'capa-3.svg' => ['Matriz de aplicações', 1200, 900],
        ];

        /** @var array<string, Media> $media */
        $media = [];

        foreach ($mediaFiles as $file => [$alt, $width, $height]) {
            $path = 'demo/'.$file;

            $media[$file] = Media::query()->updateOrCreate(
                ['path' => $path],
                [
                    'uploaded_by' => $admin->id,
                    'disk' => 'public',
                    'original_name' => $file,
                    'mime' => 'image/svg+xml',
                    'size' => (int) filesize(storage_path('app/public/'.$path)),
                    'width' => $width,
                    'height' => $height,
                    'alt' => $alt,
                    'caption' => null,
                    'hash' => hash_file('sha256', storage_path('app/public/'.$path)),
                ],
            );
        }

        // ---------------------------------------------------------------- pages

        $published = [
            'status' => PageStatus::Published,
            'published_at' => $now->copy()->subMonth(),
        ];

        Page::query()->firstOrCreate(
            ['slug' => 'home'],
            ['title' => 'Home', 'blocks' => [], 'status' => PageStatus::Published, 'published_at' => $now->copy()],
        );
        Page::query()->firstOrCreate(
            ['slug' => '404'],
            [
                'title' => 'Página não encontrada',
                'blocks' => [
                    [
                        'id' => 'not-found-hero',
                        'type' => 'hero',
                        'title' => 'Página não encontrada',
                        'subtitle' => 'O endereço que você tentou acessar não existe ou foi movido.',
                        'button_label' => 'Voltar para o início',
                        'button_href' => '/',
                    ],
                ],
                'status' => PageStatus::Published,
                'published_at' => $now->copy(),
            ],
        );

        $home = Page::query()->where('slug', 'home')->firstOrFail();
        $home->update([
            'title' => 'Home',
            'blocks' => [
                [
                    'id' => 'home-hero',
                    'type' => 'hero',
                    'title' => 'Resistências industriais feitas para o turno inteiro.',
                    'subtitle' => 'Projetamos e fabricamos resistências elétricas sob medida para fornos, tanques, prensas e linhas de extrusão. Do desenho ao ensaio térmico, tudo em uma casa.',
                    'button_label' => 'Ver produtos',
                    'button_href' => '/produtos',
                ],
                [
                    'id' => 'home-categories',
                    'type' => 'categories',
                    'title' => 'Compre por linha',
                    'subtitle' => 'Atalhos para as famílias de resistências e acessórios.',
                ],
                [
                    'id' => 'home-grid',
                    'type' => 'grid',
                    'title' => 'O que resolvemos no chão de fábrica',
                    'items' => [
                        [
                            'title' => 'Aquecimento sob medida',
                            'description' => 'Cálculo térmico, desenho e fabricação conforme a aplicação — sem adaptar o processo ao que existe em catálogo.',
                        ],
                        [
                            'title' => 'Reposição sem parada longa',
                            'description' => 'Identificamos a resistência pela amostra ou desenho e produzimos lotes de reposição com prazo combinado antes de começar.',
                        ],
                        [
                            'title' => 'Assistência e ensaio',
                            'description' => 'Medição de resistência, teste de isolação e laudo simples do lote antes do envio. O técnico instala com o dado na mão.',
                        ],
                    ],
                ],
                [
                    'id' => 'home-products',
                    'type' => 'products',
                    'title' => 'Destaques do catálogo',
                    'limit' => 4,
                ],
                [
                    'id' => 'home-posts',
                    'type' => 'posts',
                    'title' => 'Notas técnicas',
                    'limit' => 3,
                ],
                [
                    'id' => 'home-testimonials',
                    'type' => 'testimonials',
                    'title' => 'Quem depende da gente',
                ],
                [
                    'id' => 'home-clients',
                    'type' => 'clients',
                    'title' => 'Indústrias atendidas',
                ],
                [
                    'id' => 'home-faq',
                    'type' => 'faq',
                    'title' => 'Dúvidas de quem compra',
                ],
                [
                    'id' => 'home-cta',
                    'type' => 'cta',
                    'title' => 'Tem uma resistência para repor ou um projeto novo?',
                    'description' => 'Envie o desenho, a foto ou a amostra. Respondemos com proposta técnica em até um dia útil.',
                    'button_label' => 'Falar com engenharia',
                    'button_href' => '/contato',
                ],
                [
                    'id' => 'home-newsletter',
                    'type' => 'newsletter',
                    'title' => 'Receba notas técnicas',
                    'description' => 'Um e-mail por mês com cálculo, manutenção e casos de aplicação. Sem propaganda.',
                ],
            ],
        ]);

        $sobre = Page::query()->where('slug', 'nova-pagina')->first();

        if ($sobre === null) {
            $sobre = Page::query()->where('slug', 'sobre-nos')->first();
        }

        $sobre ??= new Page;
        $sobre->fill([
            'title' => 'Sobre nós',
            'slug' => 'sobre-nos',
            'icon' => null,
            'is_service' => false,
            'blocks' => [
                [
                    'id' => 'sobre-hero',
                    'type' => 'hero',
                    'title' => 'Uma fábrica pequena o bastante para ouvir o cliente.',
                    'subtitle' => 'Desde 2001, fabricamos resistências elétricas em Caxias do Sul para indústrias que não podem parar. Ainda hoje, cada pedido passa pela mão de um engenheiro antes de virar chapa.',
                    'button_label' => null,
                    'button_href' => null,
                ],
                [
                    'id' => 'sobre-texto',
                    'type' => 'text',
                    'body' => "A Termoflux nasceu dentro de uma metalúrgica, resolvendo um problema simples: a resistência do forno queimava a cada três meses e ninguém sabia dizer por quê. O primeiro pedido foi um desenho à mão e uma resistência tubular feita em duas semanas.\n\nVinte e quatro anos depois, a bancada virou uma fábrica de 3.200 m², mas o método é o mesmo. Entendemos o processo, calculamos a carga térmica e só então escolhemos bainha, liga e terminal. Quando o cliente manda uma foto da peça quebrada, a primeira pergunta ainda é sobre a temperatura de trabalho.\n\nTrabalhamos com aço inox 304 e 316, Inconel, latão e cobre. Produzimos desde uma peça única de reposição até lotes mensais para linhas de extrusão. Em todos os casos, o lote sai com medição de resistência, teste de isolação e a identificação do pedido gravada no terminal.\n\nNão somos a maior fábrica de resistências do país. Preferimos ser a que devolve o telefone no mesmo dia.",
                ],
                [
                    'id' => 'sobre-numeros',
                    'type' => 'grid',
                    'title' => 'A fábrica em números',
                    'items' => [
                        ['title' => '24 anos', 'description' => 'de operação contínua em Caxias do Sul.'],
                        ['title' => '3.200 m²', 'description' => 'de área industrial, com bancada de ensaio própria.'],
                        ['title' => '180 projetos', 'description' => 'sob medida entregues por ano, em média.'],
                        ['title' => '48 horas', 'description' => 'para devolver uma proposta técnica com desenho.'],
                        ['title' => '34 pessoas', 'description' => 'entre bancada, engenharia e administrativo.'],
                        ['title' => '12 meses', 'description' => 'de garantia contra defeito de fabricação.'],
                    ],
                ],
                [
                    'id' => 'sobre-imagem',
                    'type' => 'image',
                    'src' => $mediaUrl('fabrica.svg'),
                    'alt' => 'Linha de montagem da fábrica Termoflux',
                    'caption' => 'Bancada de montagem e ensaio — Distrito Industrial, Caxias do Sul.',
                    'media_id' => $media['fabrica.svg']->id,
                ],
                [
                    'id' => 'sobre-clientes',
                    'type' => 'clients',
                    'title' => 'Algumas indústrias que atendemos',
                ],
                [
                    'id' => 'sobre-cta',
                    'type' => 'cta',
                    'title' => 'Quer conhecer a fábrica?',
                    'description' => 'Agendamos visitas técnicas nas terças e quintas, das 9h às 15h.',
                    'button_label' => 'Agendar visita',
                    'button_href' => '/contato',
                ],
            ],
            ...$published,
        ]);
        $sobre->save();

        $servicos = Page::query()->updateOrCreate(
            ['slug' => 'servicos'],
            [
                'title' => 'Serviços',
                'blocks' => [
                    [
                        'id' => 'serv-hero',
                        'type' => 'hero',
                        'title' => 'Engenharia térmica do desenho à reposição.',
                        'subtitle' => 'Quatro frentes de trabalho, uma mesma pergunta: qual é a condição real de operação? A resposta define material, potência e geometria.',
                        'button_label' => 'Solicitar orçamento',
                        'button_href' => '/orcamento',
                    ],
                    [
                        'id' => 'serv-grid',
                        'type' => 'grid',
                        'title' => 'Onde atuamos',
                        'items' => [
                            [
                                'title' => 'Fornos e estufas',
                                'description' => 'Resistências tubulares, aletadas e de fita para fornos de tratamento térmico, estufas de secagem e muflas.',
                            ],
                            [
                                'title' => 'Tanques e imersão',
                                'description' => 'Aquecimento de água, óleo, banhos de fosfato e soluções ácidas, com flange ou rosca conforme o vaso.',
                            ],
                            [
                                'title' => 'Extrusão, sopro e prensas',
                                'description' => 'Cartuchos de alta carga, anéis de aquecimento e resistências planas para matrizes e canhões.',
                            ],
                            [
                                'title' => 'Manutenção e reposição',
                                'description' => 'Identificação por amostra, produção de lote de reposição e ensaio de isolação antes do envio.',
                            ],
                            [
                                'title' => 'Painéis e controle',
                                'description' => 'Montagem de painéis com controlador PID, termopares e proteção, integrados à resistência.',
                            ],
                            [
                                'title' => 'Ensaios e laudos',
                                'description' => 'Medição de resistência, isolação e estanqueidade, com laudo assinado por engenheiro.',
                            ],
                        ],
                    ],
                    [
                        'id' => 'serv-texto',
                        'type' => 'text',
                        'body' => "Material não se escolhe por preço. Escolhemos a liga pela temperatura de trabalho, pela atmosfera do forno e pelo tempo de ciclo. Aço inox 304 resolve a maior parte das aplicações até 400 °C. Acima disso, ou em ambiente com enxofre, entram 316, Inconel e bainhas cerâmicas.\n\nPotência também não é chute. Um cálculo mal feito entrega aquecimento lento, consumo alto e vida curta. Por isso pedimos temperatura inicial, temperatura final, massa aquecida e tempo desejado antes de propor qualquer peça.\n\nO mesmo vale para o terminal. Um borne subdimensionado derrete antes da resistência queimar e esconde o problema real. Nossos projetos saem com terminal, vedação e comprimento de cabo definidos para a caixa onde a peça vai morar.",
                    ],
                    [
                        'id' => 'serv-cta',
                        'type' => 'cta',
                        'title' => 'Não sabe qual resistência precisa?',
                        'description' => 'Mande a foto da peça e a temperatura de trabalho. A engenharia responde com o caminho mais curto.',
                        'button_label' => 'Enviar dados',
                        'button_href' => '/contato',
                    ],
                ],
                ...$published,
                'created_at' => $now->copy(),
                'updated_at' => $now->copy(),
            ],
        );

        Page::query()->updateOrCreate(
            ['slug' => 'contato'],
            [
                'title' => 'Contato',
                'blocks' => [
                    [
                        'id' => 'contato-hero',
                        'type' => 'hero',
                        'title' => 'Fale com a engenharia.',
                        'subtitle' => 'Conte o processo, a temperatura e a tensão disponível. Se tiver foto ou desenho, melhor ainda — respondemos em até um dia útil.',
                        'button_label' => null,
                        'button_href' => null,
                    ],
                    [
                        'id' => 'contato-grid',
                        'type' => 'grid',
                        'title' => 'Onde estamos',
                        'items' => [
                            ['title' => 'Fábrica', 'description' => 'Rua das Indústrias, 1420 — Distrito Industrial, Caxias do Sul/RS'],
                            ['title' => 'Telefone', 'description' => '(54) 3211-4480 — segunda a sexta, 7h30 às 17h30'],
                            ['title' => 'E-mail', 'description' => 'contato@termoflux.com.br — resposta em até um dia útil'],
                        ],
                    ],
                    [
                        'id' => 'contato-form',
                        'type' => 'contact',
                        'title' => 'Solicitar orçamento técnico',
                        'subtitle' => 'Temperatura de trabalho, tensão disponível e tempo de aquecimento já ajudam bastante.',
                    ],
                ],
                ...$published,
                'created_at' => $now->copy()->addMinute(),
                'updated_at' => $now->copy()->addMinute(),
            ],
        );

        Page::query()->updateOrCreate(
            ['slug' => 'trabalhe-conosco'],
            [
                'title' => 'Trabalhe conosco',
                'blocks' => [
                    [
                        'id' => 'carreira-hero',
                        'type' => 'hero',
                        'title' => 'Trabalhe na Termoflux.',
                        'subtitle' => 'Somos 34 pessoas entre bancada, engenharia e administrativo. Quem entra aprende a medir, desenhar e testar — não só a montar.',
                        'button_label' => null,
                        'button_href' => null,
                    ],
                    [
                        'id' => 'carreira-texto',
                        'type' => 'text',
                        'body' => "Procuramos gente com prática de bancada e vontade de entender o porquê. Experiência em caldeiraria, elétrica industrial ou usinagem ajuda, mas não é obrigatória para a vaga de montador.\n\nPara engenharia, valorizamos quem já calculou carga térmica em processo real e sabe ler um desenho de forno. Para o administrativo, organização e boa escrita contam mais do que currículo longo.\n\nOferecemos registro em carteira, plano de saúde, vale-refeição e curso técnico pago quando a função exige. O horário é de fábrica: entra cedo, sai cedo.",
                    ],
                    [
                        'id' => 'carreira-form',
                        'type' => 'career',
                        'title' => 'Envie seu currículo',
                        'subtitle' => 'Conte em poucas linhas o que você já fez e o que gostaria de fazer aqui.',
                    ],
                ],
                ...$published,
                'created_at' => $now->copy()->addMinutes(2),
                'updated_at' => $now->copy()->addMinutes(2),
            ],
        );

        // ---------------------------------------------------------------- catalog

        $categories = [
            'tubulares' => 'Resistências tubulares',
            'cartucho' => 'Resistências cartucho',
            'imersao' => 'Resistências de imersão',
            'acessorios' => 'Termopares e acessórios',
        ];

        $categoryModels = [];

        foreach (array_values($categories) as $position => $name) {
            $slug = array_search($name, $categories, true);

            $categoryModels[$slug] = CatalogCategory::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'parent_id' => null, 'position' => $position],
            );
        }

        $products = [
            [
                'slug' => 'resistencia-tubular-u-3kw',
                'name' => 'Resistência tubular em U — 3 kW',
                'sku' => 'TL-TU-3000',
                'price' => '289.00',
                'categories' => ['tubulares'],
                'media' => 'resistencia-tubular.svg',
                'position' => 0,
                'description' => "Bainha em aço inox 304, 8 mm, curvada em U.\n\nPotência: 3 kW · Tensão: 220 V\nComprimento desenvolvido: 1.200 mm\nTerminais: parafuso M4 com porca e arruela\nTemperatura máxima de bainha: 450 °C\n\nAplicação típica: fornos de secagem, estufas e aquecimento de ar. Fornecido com medição de resistência e teste de isolação.",
            ],
            [
                'slug' => 'resistencia-tubular-aletada-2kw',
                'name' => 'Resistência tubular aletada — 2 kW',
                'sku' => 'TL-TA-2000',
                'price' => '349.00',
                'categories' => ['tubulares'],
                'media' => 'resistencia-aletada.svg',
                'position' => 1,
                'description' => "Aletas em aço carbono soldadas sobre bainha inox, para troca térmica em ar forçado.\n\nPotência: 2 kW · Tensão: 220 V\nComprimento: 800 mm · Aletas: 40 mm\nTemperatura máxima: 400 °C\n\nIndicada para estufas com circulação forçada e túneis de secagem. Sob consulta em outras potências e comprimentos.",
            ],
            [
                'slug' => 'resistencia-cartucho-alta-carga-1000w',
                'name' => 'Resistência cartucho alta carga — 1.000 W',
                'sku' => 'TL-RC-1000',
                'price' => '189.90',
                'categories' => ['cartucho'],
                'media' => 'resistencia-cartucho.svg',
                'position' => 2,
                'description' => "Cartucho de alta densidade para canhões de extrusão e matrizes.\n\nPotência: 1.000 W · Tensão: 220 V\nDiâmetro: 12,5 mm · Comprimento: 120 mm\nCarga superficial: 12 W/cm²\nTerminais: cabo de 300 mm com luva de fibra de vidro\n\nBainha em inox 321. Fabricação sob medida a partir do diâmetro e do comprimento do alojamento.",
            ],
            [
                'slug' => 'resistencia-cartucho-com-termopar-630w',
                'name' => 'Resistência cartucho com termopar — 630 W',
                'sku' => 'TL-RC-630',
                'price' => '249.90',
                'categories' => ['cartucho'],
                'media' => 'resistencia-cartucho.svg',
                'position' => 3,
                'description' => "Cartucho com canal interno para termopar tipo J, evitando furo adicional na matriz.\n\nPotência: 630 W · Tensão: 220 V\nDiâmetro: 10 mm · Comprimento: 100 mm\nTermopar: tipo J, cabo de 2 m\n\nFornecido com o termopar instalado e identificado. Reposição sob desenho ou amostra.",
            ],
            [
                'slug' => 'resistencia-imersao-flangeada-9kw',
                'name' => 'Resistência de imersão flangeada — 9 kW',
                'sku' => 'TL-RI-9000',
                'price' => '1890.00',
                'categories' => ['imersao'],
                'media' => 'resistencia-imersao.svg',
                'position' => 4,
                'description' => "Três tubos em U sobre flange DN 80, para aquecimento de água e soluções aquosas.\n\nPotência: 9 kW · Tensão: 380 V trifásico\nBainha: inox 316 · Comprimento imerso: 700 mm\nFlange: DN 80, 4 furos\nTemperatura máxima: 90 °C na água\n\nFornecido com junta de vedação e caixa de ligação. Opcional com termostato de segurança.",
            ],
            [
                'slug' => 'resistencia-imersao-rosqueada-3kw',
                'name' => 'Resistência de imersão rosqueada — 3 kW',
                'sku' => 'TL-RI-3000',
                'price' => '640.00',
                'categories' => ['imersao'],
                'media' => 'resistencia-imersao.svg',
                'position' => 5,
                'description' => "Rosca de 1¼\" em latão, para tanques de pequeno porte e banhos de óleo.\n\nPotência: 3 kW · Tensão: 220 V\nBainha: inox 304 · Comprimento imerso: 400 mm\nTemperatura máxima: 200 °C no óleo\n\nInclui porca de vedação. Indicada para manutenção de tanques de fosfato e desengraxantes.",
            ],
            [
                'slug' => 'termopar-tipo-k',
                'name' => 'Termopar tipo K com conector padrão',
                'sku' => 'TL-TK-200',
                'price' => '138.00',
                'categories' => ['acessorios'],
                'media' => 'termopar.svg',
                'position' => 6,
                'description' => "Sensor tipo K com bainha inox de 6 mm e conector padrão.\n\nFaixa: 0 a 1.100 °C\nComprimento da bainha: 200 mm\nCabo: 2 m com malha de aço\nConector: minitermopar macho\n\nTambém fornecido em tipos J e PT100, sob consulta.",
            ],
            [
                'slug' => 'controlador-de-temperatura-pid',
                'name' => 'Controlador de temperatura PID',
                'sku' => 'TL-CT-100',
                'price' => '479.00',
                'categories' => ['acessorios'],
                'media' => 'painel.svg',
                'position' => 7,
                'description' => "Controlador de painel 48 x 48 mm com saída por relé e alarme.\n\nEntrada: termopar tipo K ou PT100\nSaída: relé 250 V / 5 A\nAlimentação: 100 a 240 V\nPrecisão: ±1 °C\n\nAcompanha manual em português. Montagem em painel com trava de fixação.",
            ],
        ];

        $productModels = [];

        foreach ($products as $data) {
            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'position' => $data['position'],
                    'translations' => null,
                ],
            );

            $product->categories()->sync(
                array_map(fn (string $slug): int => $categoryModels[$slug]->id, $data['categories']),
            );
            $product->media()->sync([$media[$data['media']]->id]);

            $productModels[$data['slug']] = $product;
        }

        $related = [
            ['resistencia-tubular-u-3kw', 'resistencia-tubular-aletada-2kw'],
            ['resistencia-cartucho-alta-carga-1000w', 'resistencia-cartucho-com-termopar-630w'],
            ['resistencia-imersao-flangeada-9kw', 'resistencia-imersao-rosqueada-3kw'],
            ['termopar-tipo-k', 'controlador-de-temperatura-pid'],
        ];

        foreach ($related as [$a, $b]) {
            $productModels[$a]->related()->syncWithoutDetaching([$productModels[$b]->id]);
            $productModels[$b]->related()->syncWithoutDetaching([$productModels[$a]->id]);
        }

        // ---------------------------------------------------------------- blog

        $blogCategories = [
            'engenharia-termica' => 'Engenharia térmica',
            'manutencao-industrial' => 'Manutenção industrial',
            'aplicacoes' => 'Aplicações',
        ];

        $blogCategoryModels = [];

        foreach ($blogCategories as $slug => $name) {
            $blogCategoryModels[$slug] = BlogCategory::query()->updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $posts = [
            [
                'slug' => 'por-que-a-resistencia-queima-antes-do-prazo',
                'title' => 'Por que a resistência queima antes do prazo',
                'category' => 'manutencao-industrial',
                'cover' => 'capa-2.svg',
                'published_at' => $now->copy()->subDays(6),
                'excerpt' => 'Na maioria dos casos a peça não falhou sozinha: o problema está na carga, na ventilação ou no terminal.',
                'body' => "Resistência queimada raramente é azar. Em quase todo laudo que fazemos, a peça trabalhava fora da condição para a qual foi projetada — e o desgaste só apareceu no inox.\n\nO primeiro suspeito é a carga superficial. Um cartucho com 12 W/cm² montado num alojamento folgado perde contato térmico, esquenta por fora e queima a bainha antes do prazo. O segundo é a ventilação: forno com fluxo de ar menor do que o previsto reduz a troca térmica e eleva a temperatura da bainha.\n\nO terceiro, mais barato de resolver, é o terminal. Borne mal apertado, cabo subdimensionado ou emenda fora da caixa de ligação criam ponto quente. Nesse caso a resistência está boa: quem queima é a conexão.\n\nAntes de trocar a peça, vale medir a resistência a frio e comparar com o valor de projeto. Diferença acima de 10% indica envelhecimento; isolação abaixo de 1 MΩ indica umidade ou trinca na vedação. Esses dois números explicam a maior parte das falhas repetidas.\n\nSe a mesma posição queima três vezes no ano, o problema não é a resistência. É o projeto.",
            ],
            [
                'slug' => 'cartucho-ou-tubular-como-escolher',
                'title' => 'Cartucho ou tubular: como escolher',
                'category' => 'engenharia-termica',
                'cover' => 'capa-1.svg',
                'published_at' => $now->copy()->subDays(15),
                'excerpt' => 'A geometria do alojamento decide mais do que a potência. Veja quando cada tipo faz sentido.',
                'body' => "A pergunta chega sempre igual: 'preciso de 2 kW, qual resistência uso?'. A potência é o último dado que importa. Antes dela vêm o formato do alojamento, a temperatura de trabalho e a forma de troca térmica.\n\nCartucho existe para furo cilíndrico. Ele aquece por contato direto com a parede do alojamento, então o ajuste é crítico: folga de mais de 0,1 mm já derruba a eficiência. É a escolha para canhão de extrusão, matriz, chapa de prensa e qualquer bloco maciço.\n\nTubular existe para espaço livre. Aquece o ar ou o líquido ao redor, e por isso depende de circulação. É a escolha para forno, estufa, tanque e duto. Curvada, resolve geometrias impossíveis para o cartucho.\n\nHá casos em que os dois competem. Numa matriz grande, o cartucho aquece mais rápido e gasta menos, mas exige furação precisa. A tubular é mais tolerante e mais fácil de repor, porém aquece devagar e ocupa espaço.\n\nSe a peça original é importada e não há desenho, mande a amostra. Medimos diâmetro, comprimento, posição do terminal e rigidez da bainha. Em geral, a reprodução fica mais barata do que o frete do exterior.",
            ],
            [
                'slug' => 'aquecimento-de-tanques-potencia-sem-chute',
                'title' => 'Aquecimento de tanques: potência sem chute',
                'category' => 'engenharia-termica',
                'cover' => 'capa-3.svg',
                'published_at' => $now->copy()->subDays(28),
                'excerpt' => 'Massa, delta de temperatura e tempo definem a potência. O resto é perda térmica — e ela não pode ser ignorada.',
                'body' => "Para aquecer um tanque, a conta começa em três números: massa de líquido, diferença de temperatura e tempo desejado. Potência mínima é massa × calor específico × delta T, dividida pelo tempo. Para água, 1 kcal aquece 1 kg em 1 °C.\n\nUm tanque de 500 litros que precisa subir de 20 °C para 80 °C em quatro horas pede cerca de 8,7 kW só para o aquecimento. A esse valor soma-se a perda pelas paredes, que em tanque sem isolamento pode chegar a 30%.\n\nNa prática, trabalhamos com 20% de folga sobre o cálculo e distribuição de carga em mais de uma resistência. Isso permite manutenção sem parar o processo e evita que uma única peça trabalhe no limite.\n\nTambém importa onde a resistência fica. Peça encostada na parede cria ponto quente e carboniza o produto. Peça no fundo, sem circulação, estratifica o banho: a parte de cima ferve enquanto o resto continua frio.\n\nSe o tanque tem agitação, o cálculo muda para melhor. Se não tem, a recomendação é instalar ou usar resistência com maior área de troca — nunca aumentar a potência e esperar que resolva.",
            ],
            [
                'slug' => 'o-que-enviar-para-um-orcamento-de-reposicao',
                'title' => 'O que enviar para um orçamento de reposição',
                'category' => 'manutencao-industrial',
                'cover' => 'capa-2.svg',
                'published_at' => $now->copy()->subDays(41),
                'excerpt' => 'Quatro informações bastam para sair do orçamento genérico e receber uma peça que encaixa.',
                'body' => "Orçamento de resistência sem dado técnico vira chute. Para responder com precisão, precisamos de quatro coisas: foto da peça inteira, medidas do alojamento, tensão disponível e temperatura de trabalho.\n\nA foto deve mostrar o terminal e o comprimento total. Se houver etiqueta ou gravação, uma foto aproximada resolve a identificação. Peça quebrada também serve: o ponto de ruptura conta a história do problema.\n\nAs medidas vêm do alojamento, não da peça velha. Diâmetro do furo, profundidade útil e distância entre centros dos terminais. Em resistência tubular, o comprimento desenvolvido e o raio das curvas.\n\nTensão e ligação: 220 V monofásico, 380 V trifásico, delta ou estrela. Essa informação evita a peça certa ligada do jeito errado, que queima em minutos.\n\nPor último, a temperatura de trabalho e o ambiente. Água, óleo, ar, forno com atmosfera? Cada resposta muda a liga e a vedação. Com esses dados, a proposta sai em até dois dias úteis e a peça, no prazo combinado.",
            ],
        ];

        foreach ($posts as $data) {
            Post::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'category_id' => $blogCategoryModels[$data['category']]->id,
                    'excerpt' => $data['excerpt'],
                    'body' => $data['body'],
                    'cover' => $mediaUrl($data['cover']),
                    'published_at' => $data['published_at'],
                ],
            );
        }

        // ---------------------------------------------------------------- content

        $testimonials = [
            ['Ricardo Basso', 'Supervisor de manutenção', 'Metalúrgica Andrade', 'Comparamos com três fornecedores. A Termoflux foi a única que pediu o desenho do forno antes de mandar preço. A resistência chegou com a curva certa e instalou sem retrabalho.'],
            ['Cláudia Menezes', 'Engenheira de processos', 'Química Sul', 'A resistência de imersão chegou no prazo e com o laudo de isolação. Instalamos direto, sem parar o tanque por um dia inteiro como acontecia antes.'],
            ['Fábio Klein', 'Supervisor de produção', 'Plásticos Vale', 'Repõem o mesmo cartucho há seis anos. Quando precisamos de uma variação, o desenho volta em dois dias e a peça em duas semanas.'],
        ];

        foreach ($testimonials as $position => [$author, $role, $company, $quote]) {
            Testimonial::query()->updateOrCreate(
                ['author' => $author],
                ['role' => $role.' · '.$company, 'quote' => $quote, 'sort_order' => $position],
            );
        }

        $faqs = [
            ['Vocês fabricam sob medida?', 'Sim. A maior parte da produção é sob desenho ou amostra. Envie a peça, a foto com medidas ou o desenho técnico e devolvemos uma proposta com geometria, material e potência definidos.'],
            ['Qual é o prazo de entrega?', 'Peças de linha saem em até 10 dias úteis. Sob medida, o prazo varia de 15 a 25 dias úteis após a aprovação do desenho. Reposição emergencial pode ser avaliada caso a caso.'],
            ['Como enviar um pedido de reposição?', 'O caminho mais rápido é pelo formulário de contato, com foto da peça, medidas do alojamento, tensão e temperatura de trabalho. Também atendemos por telefone e WhatsApp no horário comercial.'],
            ['Vocês emitem laudo dos ensaios?', 'Sim. Todo lote sai com medição de resistência a frio e teste de isolação. Para aplicações críticas, emitimos laudo assinado por engenheiro responsável.'],
            ['Qual é a garantia?', 'Doze meses contra defeito de fabricação, desde que a peça seja usada na condição informada no pedido. A garantia não cobre falha por tensão incorreta, falta de ventilação ou terminal mal apertado.'],
            ['Atendem fora do Rio Grande do Sul?', 'Atendemos todo o Brasil por transportadora. Para peças longas, usamos embalagem rígida e enviamos o comprovante de postagem no mesmo dia.'],
        ];

        foreach ($faqs as $position => [$question, $answer]) {
            Faq::query()->updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $position],
            );
        }

        $clients = [
            'Metalúrgica Andrade',
            'Plásticos Vale',
            'Química Sul',
            'Alimentos Bom Sabor',
            'Autopeças Rota',
            'Cervejaria Serra',
        ];

        foreach ($clients as $position => $name) {
            Client::query()->updateOrCreate(
                ['name' => $name],
                ['logo' => null, 'url' => null, 'sort_order' => $position],
            );
        }

        $stores = [
            ['Termoflux — Matriz', StoreKind::Physical, 'Rua das Indústrias, 1420 — Distrito Industrial, Caxias do Sul/RS', '(54) 3211-4480', null, 0],
            ['Distribuidor Sul', StoreKind::Physical, 'Av. das Indústrias, 900 — Porto Alegre/RS', '(51) 3222-1180', null, 1],
            ['Loja virtual Termoflux', StoreKind::Online, null, null, 'https://termoflux.com.br/loja', 2],
            ['Mercado Industrial', StoreKind::Online, null, null, 'https://mercadoindustrial.com.br', 3],
        ];

        foreach ($stores as [$name, $kind, $address, $phone, $url, $position]) {
            Store::query()->updateOrCreate(
                ['name' => $name],
                ['kind' => $kind, 'address' => $address, 'phone' => $phone, 'url' => $url, 'position' => $position],
            );
        }

        $this->translateDemo();
    }

    private function copyAssets(): void
    {
        $source = database_path('seeders/demo');
        $target = storage_path('app/public/demo');

        if (! File::isDirectory($target)) {
            File::makeDirectory($target, 0755, true);
        }

        foreach (File::files($source) as $file) {
            File::copy($file->getPathname(), $target.'/'.$file->getFilename());
        }
    }

    private function translateDemo(): void
    {
        foreach ($this->pageTranslations() as $slug => $locales) {
            $page = Page::query()->where('slug', (string) $slug)->first();

            if ($page === null) {
                continue;
            }

            $translations = [];

            foreach ($locales as $locale => $data) {
                $translations[$locale] = [
                    'title' => $data['title'] ?? $page->title,
                    'blocks' => $this->translatedBlocks($page, $data['blocks'] ?? []),
                ];
            }

            $page->translations = $translations;
            $page->save();
        }

        foreach ($this->productTranslations() as $slug => $translations) {
            Product::query()->where('slug', $slug)->first()?->update(['translations' => $translations]);
        }

        foreach ($this->categoryTranslations() as $slug => $translations) {
            CatalogCategory::query()->where('slug', $slug)->first()?->update(['translations' => $translations]);
        }

        foreach ($this->postTranslations() as $slug => $translations) {
            Post::query()->where('slug', $slug)->first()?->update(['translations' => $translations]);
        }

        foreach ($this->faqTranslations() as $question => $translations) {
            Faq::query()->where('question', $question)->first()?->update(['translations' => $translations]);
        }

        foreach ($this->testimonialTranslations() as $author => $translations) {
            Testimonial::query()->where('author', $author)->first()?->update(['translations' => $translations]);
        }
    }

    /**
     * @param  array<string, array<string, mixed>>  $overrides
     * @return array<int, array<string, mixed>>
     */
    private function translatedBlocks(Page $page, array $overrides): array
    {
        return array_map(
            fn (array $block): array => array_replace($block, $overrides[$block['id']] ?? []),
            array_values($page->blocks ?? []),
        );
    }

    /**
     * @return array<int|string, array<string, array{title?: string, blocks?: array<string, array<string, mixed>>}>>
     */
    private function pageTranslations(): array
    {
        return [
            'home' => [
                'en' => [
                    'title' => 'Home',
                    'blocks' => [
                        'home-hero' => [
                            'title' => 'Industrial heating elements built for the whole shift.',
                            'subtitle' => 'We design and manufacture custom electric heating elements for furnaces, tanks, presses and extrusion lines. From drawing to thermal test, all under one roof.',
                            'button_label' => 'View products',
                        ],
                        'home-categories' => [
                            'title' => 'Shop by line',
                            'subtitle' => 'Shortcuts to the heating element families and accessories.',
                        ],
                        'home-grid' => [
                            'title' => 'What we solve on the factory floor',
                            'items' => [
                                [
                                    'title' => 'Custom heating',
                                    'description' => 'Thermal calculation, design and manufacturing for the application — without bending the process to fit a catalog item.',
                                ],
                                [
                                    'title' => 'Replacement without long downtime',
                                    'description' => 'We identify the element from a sample or drawing and produce replacement batches with an agreed lead time.',
                                ],
                                [
                                    'title' => 'Support and testing',
                                    'description' => 'Resistance measurement, insulation testing and a simple batch report before shipping. The technician installs with the data in hand.',
                                ],
                            ],
                        ],
                        'home-products' => ['title' => 'Catalog highlights'],
                        'home-posts' => ['title' => 'Technical notes'],
                        'home-testimonials' => ['title' => 'Who relies on us'],
                        'home-clients' => ['title' => 'Industries we serve'],
                        'home-faq' => ['title' => 'Buyer questions'],
                        'home-cta' => [
                            'title' => 'Need a replacement element or a new project?',
                            'description' => 'Send the drawing, photo or sample. We reply with a technical proposal within one business day.',
                            'button_label' => 'Talk to engineering',
                        ],
                        'home-newsletter' => [
                            'title' => 'Get technical notes',
                            'description' => 'One email a month with calculations, maintenance and application cases. No ads.',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Inicio',
                    'blocks' => [
                        'home-hero' => [
                            'title' => 'Resistencias industriales hechas para todo el turno.',
                            'subtitle' => 'Diseñamos y fabricamos resistencias eléctricas a medida para hornos, tanques, prensas y líneas de extrusión. Del plano al ensayo térmico, todo en un solo lugar.',
                            'button_label' => 'Ver productos',
                        ],
                        'home-categories' => [
                            'title' => 'Compre por línea',
                            'subtitle' => 'Atajos a las familias de resistencias y accesorios.',
                        ],
                        'home-grid' => [
                            'title' => 'Lo que resolvemos en el piso de fábrica',
                            'items' => [
                                [
                                    'title' => 'Calefacción a medida',
                                    'description' => 'Cálculo térmico, diseño y fabricación según la aplicación — sin adaptar el proceso a lo que existe en catálogo.',
                                ],
                                [
                                    'title' => 'Reposición sin paradas largas',
                                    'description' => 'Identificamos la resistencia por la muestra o el plano y producimos lotes de reposición con plazo acordado.',
                                ],
                                [
                                    'title' => 'Asistencia y ensayo',
                                    'description' => 'Medición de resistencia, prueba de aislamiento e informe simple del lote antes del envío. El técnico instala con el dato en la mano.',
                                ],
                            ],
                        ],
                        'home-products' => ['title' => 'Destacados del catálogo'],
                        'home-posts' => ['title' => 'Notas técnicas'],
                        'home-testimonials' => ['title' => 'Quiénes confían en nosotros'],
                        'home-clients' => ['title' => 'Industrias que atendemos'],
                        'home-faq' => ['title' => 'Dudas de quien compra'],
                        'home-cta' => [
                            'title' => '¿Necesitas reponer una resistencia o tienes un proyecto nuevo?',
                            'description' => 'Envía el plano, la foto o la muestra. Respondemos con propuesta técnica en hasta un día hábil.',
                            'button_label' => 'Hablar con ingeniería',
                        ],
                        'home-newsletter' => [
                            'title' => 'Recibe notas técnicas',
                            'description' => 'Un correo al mes con cálculo, mantenimiento y casos de aplicación. Sin publicidad.',
                        ],
                    ],
                ],
            ],
            'sobre-nos' => [
                'en' => [
                    'title' => 'About us',
                    'blocks' => [
                        'sobre-hero' => [
                            'title' => 'A factory small enough to listen to the customer.',
                            'subtitle' => 'Since 2001 we have manufactured electric heating elements in Caxias do Sul for industries that cannot stop. Every order still passes through an engineer hands before it becomes hardware.',
                        ],
                        'sobre-texto' => [
                            'body' => "Termoflux was born inside a metalworking shop, solving a simple problem: the furnace element burned every three months and nobody could say why. The first order was a hand-drawn sketch and a tubular element made in two weeks. Twenty-four years later, the bench became a 3,200 m² factory, but the method is the same: we understand the process, calculate the thermal load, and only then choose sheath, alloy and terminal. When a customer sends a photo of a broken part, the first question is still about the working temperature.\n\nWe work with 304 and 316 stainless steel, Inconel, brass and copper. We produce everything from a single replacement part to monthly batches for extrusion lines. In every case, the batch ships with resistance measurement, insulation testing and the order ID engraved on the terminal.\n\nWe are not the largest heating element factory in the country. We would rather be the one that calls back the same day.",
                        ],
                        'sobre-numeros' => [
                            'title' => 'The factory in numbers',
                            'items' => [
                                ['title' => '24 years', 'description' => 'of continuous operation in Caxias do Sul.'],
                                ['title' => '3,200 m²', 'description' => 'of industrial area with its own test bench.'],
                                ['title' => '180 projects', 'description' => 'custom-built per year, on average.'],
                                ['title' => '48 hours', 'description' => 'to return a technical proposal with a drawing.'],
                                ['title' => '34 people', 'description' => 'across the bench, engineering and administration.'],
                                ['title' => '12 months', 'description' => 'warranty against manufacturing defects.'],
                            ],
                        ],
                        'sobre-clientes' => ['title' => 'Some industries we serve'],
                        'sobre-cta' => [
                            'title' => 'Want to visit the factory?',
                            'description' => 'We schedule technical visits on Tuesdays and Thursdays, 9am to 3pm.',
                            'button_label' => 'Schedule a visit',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Sobre nosotros',
                    'blocks' => [
                        'sobre-hero' => [
                            'title' => 'Una fábrica lo bastante pequeña para escuchar al cliente.',
                            'subtitle' => 'Desde 2001 fabricamos resistencias eléctricas en Caxias do Sul para industrias que no pueden parar. Cada pedido pasa por las manos de un ingeniero antes de convertirse en chapa.',
                        ],
                        'sobre-texto' => [
                            'body' => "Termoflux nació dentro de una metalúrgica, resolviendo un problema simple: la resistencia del horno se quemaba cada tres meses y nadie sabía decir por qué. El primer pedido fue un dibujo a mano y una resistencia tubular hecha en dos semanas. Veinticuatro años después, el banco se convirtió en una fábrica de 3.200 m², pero el método es el mismo: entendemos el proceso, calculamos la carga térmica y solo entonces elegimos vaina, aleación y terminal. Cuando el cliente manda la foto de la pieza rota, la primera pregunta sigue siendo la temperatura de trabajo.\n\nTrabajamos con acero inoxidable 304 y 316, Inconel, latón y cobre. Producimos desde una pieza única de reposición hasta lotes mensuales para líneas de extrusión. En todos los casos, el lote sale con medición de resistencia, prueba de aislamiento y la identificación del pedido grabada en el terminal.\n\nNo somos la fábrica de resistencias más grande del país. Preferimos ser la que devuelve la llamada el mismo día.",
                        ],
                        'sobre-numeros' => [
                            'title' => 'La fábrica en números',
                            'items' => [
                                ['title' => '24 años', 'description' => 'de operación continua en Caxias do Sul.'],
                                ['title' => '3.200 m²', 'description' => 'de área industrial, con banco de ensayos propio.'],
                                ['title' => '180 proyectos', 'description' => 'a medida entregados por año, en promedio.'],
                                ['title' => '48 horas', 'description' => 'para devolver una propuesta técnica con plano.'],
                                ['title' => '34 personas', 'description' => 'entre banco, ingeniería y administración.'],
                                ['title' => '12 meses', 'description' => 'de garantía contra defecto de fabricación.'],
                            ],
                        ],
                        'sobre-clientes' => ['title' => 'Algunas industrias que atendemos'],
                        'sobre-cta' => [
                            'title' => '¿Quieres conocer la fábrica?',
                            'description' => 'Agendamos visitas técnicas los martes y jueves, de 9 a 15 h.',
                            'button_label' => 'Agendar visita',
                        ],
                    ],
                ],
            ],
            'servicos' => [
                'en' => [
                    'title' => 'Services',
                    'blocks' => [
                        'serv-hero' => [
                            'title' => 'Thermal engineering from drawing to replacement.',
                            'subtitle' => 'Six work fronts, one question: what is the real operating condition? The answer defines material, power and geometry.',
                            'button_label' => 'Request a quote',
                        ],
                        'serv-grid' => [
                            'title' => 'Where we work',
                            'items' => [
                                ['title' => 'Furnaces and ovens', 'description' => 'Tubular, finned and strip elements for heat treatment furnaces, drying ovens and mufles.'],
                                ['title' => 'Tanks and immersion', 'description' => 'Heating water, oil, phosphate baths and acid solutions, with flange or thread to suit the vessel.'],
                                ['title' => 'Extrusion, blow molding and presses', 'description' => 'High-load cartridges, band heaters and flat elements for dies and barrels.'],
                                ['title' => 'Maintenance and replacement', 'description' => 'Identification from a sample, replacement batch production and insulation testing before shipping.'],
                                ['title' => 'Panels and control', 'description' => 'Panel assembly with PID controller, thermocouples and protection, integrated with the element.'],
                                ['title' => 'Testing and reports', 'description' => 'Resistance, insulation and leak testing, with a report signed by an engineer.'],
                            ],
                        ],
                        'serv-texto' => [
                            'body' => "Material is not chosen by price. We choose the alloy by working temperature, furnace atmosphere and cycle time. 304 stainless steel covers most applications up to 400 °C. Above that, or in sulphur atmospheres, 316, Inconel and ceramic sheaths come in.\n\nPower is not a guess either. A bad calculation delivers slow heating, high consumption and short life. That is why we ask for initial temperature, final temperature, heated mass and desired time before proposing any part.\n\nThe same goes for the terminal. An undersized lug melts before the element burns and hides the real problem. Our designs ship with terminal, sealing and cable length defined for the box where the part will live.",
                        ],
                        'serv-cta' => [
                            'title' => 'Not sure which element you need?',
                            'description' => 'Send a photo of the part and the working temperature. Engineering replies with the shortest path.',
                            'button_label' => 'Send details',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Servicios',
                    'blocks' => [
                        'serv-hero' => [
                            'title' => 'Ingeniería térmica del plano a la reposición.',
                            'subtitle' => 'Seis frentes de trabajo, una misma pregunta: ¿cuál es la condición real de operación? La respuesta define material, potencia y geometría.',
                            'button_label' => 'Solicitar presupuesto',
                        ],
                        'serv-grid' => [
                            'title' => 'Dónde actuamos',
                            'items' => [
                                ['title' => 'Hornos y estufas', 'description' => 'Resistencias tubulares, aleteadas y de cinta para hornos de tratamiento térmico, estufas de secado y muflas.'],
                                ['title' => 'Tanques e inmersión', 'description' => 'Calentamiento de agua, aceite, baños de fosfato y soluciones ácidas, con brida o rosca según el recipiente.'],
                                ['title' => 'Extrusión, soplado y prensas', 'description' => 'Cartuchos de alta carga, anillos de calefacción y resistencias planas para matrices y cañones.'],
                                ['title' => 'Mantenimiento y reposición', 'description' => 'Identificación por muestra, producción de lote de reposición y ensayo de aislamiento antes del envío.'],
                                ['title' => 'Paneles y control', 'description' => 'Montaje de paneles con controlador PID, termopares y protección, integrados a la resistencia.'],
                                ['title' => 'Ensayos e informes', 'description' => 'Medición de resistencia, aislamiento y estanqueidad, con informe firmado por ingeniero.'],
                            ],
                        ],
                        'serv-texto' => [
                            'body' => "El material no se elige por precio. Elegimos la aleación por la temperatura de trabajo, la atmósfera del horno y el tiempo de ciclo. El acero inoxidable 304 resuelve la mayor parte de las aplicaciones hasta 400 °C. Por encima, o en ambientes con azufre, entran el 316, el Inconel y las vainas cerámicas.\n\nLa potencia tampoco es un cálculo al azar. Un cálculo mal hecho entrega calentamiento lento, consumo alto y vida corta. Por eso pedimos temperatura inicial, temperatura final, masa calentada y tiempo deseado antes de proponer cualquier pieza.\n\nLo mismo vale para el terminal. Un borne subdimensionado se derrite antes de que la resistencia se queme y esconde el problema real. Nuestros proyectos salen con terminal, sellado y longitud de cable definidos para la caja donde vivirá la pieza.",
                        ],
                        'serv-cta' => [
                            'title' => '¿No sabes qué resistencia necesitas?',
                            'description' => 'Envía la foto de la pieza y la temperatura de trabajo. Ingeniería responde con el camino más corto.',
                            'button_label' => 'Enviar datos',
                        ],
                    ],
                ],
            ],
            'contato' => [
                'en' => [
                    'title' => 'Contact',
                    'blocks' => [
                        'contato-hero' => [
                            'title' => 'Talk to engineering.',
                            'subtitle' => 'Tell us the process, temperature and available voltage. A photo or drawing helps — we reply within one business day.',
                        ],
                        'contato-grid' => [
                            'title' => 'Where we are',
                            'items' => [
                                ['title' => 'Factory', 'description' => 'Rua das Indústrias, 1420 — Distrito Industrial, Caxias do Sul/RS'],
                                ['title' => 'Phone', 'description' => '(54) 3211-4480 — Monday to Friday, 7:30am to 5:30pm'],
                                ['title' => 'Email', 'description' => 'contato@termoflux.com.br — reply within one business day'],
                            ],
                        ],
                        'contato-form' => [
                            'title' => 'Request a technical quote',
                            'subtitle' => 'Working temperature, available voltage and heating time already help a lot.',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Contacto',
                    'blocks' => [
                        'contato-hero' => [
                            'title' => 'Habla con ingeniería.',
                            'subtitle' => 'Cuenta el proceso, la temperatura y la tensión disponible. Si tienes foto o plano, mejor aún — respondemos en hasta un día hábil.',
                        ],
                        'contato-grid' => [
                            'title' => 'Dónde estamos',
                            'items' => [
                                ['title' => 'Fábrica', 'description' => 'Rua das Indústrias, 1420 — Distrito Industrial, Caxias do Sul/RS'],
                                ['title' => 'Teléfono', 'description' => '(54) 3211-4480 — lunes a viernes, 7:30 a 17:30'],
                                ['title' => 'Correo', 'description' => 'contato@termoflux.com.br — respuesta en hasta un día hábil'],
                            ],
                        ],
                        'contato-form' => [
                            'title' => 'Solicitar presupuesto técnico',
                            'subtitle' => 'La temperatura de trabajo, la tensión disponible y el tiempo de calentamiento ya ayudan bastante.',
                        ],
                    ],
                ],
            ],
            'trabalhe-conosco' => [
                'en' => [
                    'title' => 'Careers',
                    'blocks' => [
                        'carreira-hero' => [
                            'title' => 'Work at Termoflux.',
                            'subtitle' => 'We are 34 people across the bench, engineering and administration. New hires learn to measure, draw and test — not just assemble.',
                        ],
                        'carreira-texto' => [
                            'body' => "We look for people with hands-on experience and the will to understand why. Experience in boilermaking, industrial electrical work or machining helps, but it is not required for the assembler role. For engineering, we value those who have calculated thermal load in a real process and can read a furnace drawing. For administration, organisation and clear writing matter more than a long résumé.\n\nWe offer a formal contract, health insurance, meal allowance and paid technical courses when the role requires them. The schedule is a factory schedule: start early, finish early.",
                        ],
                        'carreira-form' => [
                            'title' => 'Send your résumé',
                            'subtitle' => 'Tell us in a few lines what you have done and what you would like to do here.',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Trabaja con nosotros',
                    'blocks' => [
                        'carreira-hero' => [
                            'title' => 'Trabaja en Termoflux.',
                            'subtitle' => 'Somos 34 personas entre banco, ingeniería y administración. Quien entra aprende a medir, dibujar y ensayar — no solo a montar.',
                        ],
                        'carreira-texto' => [
                            'body' => "Buscamos gente con práctica de banco y ganas de entender el porqué. La experiencia en calderería, eléctrica industrial o mecanizado ayuda, pero no es obligatoria para el puesto de montador. Para ingeniería, valoramos a quien ya calculó carga térmica en un proceso real y sabe leer un plano de horno. Para administración, la organización y la buena escritura cuentan más que un currículum largo.\n\nOfrecemos registro formal, plan de salud, vale de comida y curso técnico pagado cuando la función lo exige. El horario es de fábrica: entra temprano, sale temprano.",
                        ],
                        'carreira-form' => [
                            'title' => 'Envía tu currículum',
                            'subtitle' => 'Cuenta en pocas líneas lo que ya hiciste y lo que te gustaría hacer aquí.',
                        ],
                    ],
                ],
            ],
            '404' => [
                'en' => [
                    'title' => 'Page not found',
                    'blocks' => [
                        'not-found-hero' => [
                            'title' => 'Page not found',
                            'subtitle' => 'The address you tried does not exist or has moved.',
                            'button_label' => 'Back to home',
                        ],
                    ],
                ],
                'es' => [
                    'title' => 'Página no encontrada',
                    'blocks' => [
                        'not-found-hero' => [
                            'title' => 'Página no encontrada',
                            'subtitle' => 'La dirección que intentaste acceder no existe o fue movida.',
                            'button_label' => 'Volver al inicio',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function productTranslations(): array
    {
        return [
            'resistencia-tubular-u-3kw' => [
                'en' => ['name' => 'U-shaped tubular heating element — 3 kW'],
                'es' => ['name' => 'Resistencia tubular en U — 3 kW'],
            ],
            'resistencia-tubular-aletada-2kw' => [
                'en' => ['name' => 'Finned tubular heating element — 2 kW'],
                'es' => ['name' => 'Resistencia tubular aleteada — 2 kW'],
            ],
            'resistencia-cartucho-alta-carga-1000w' => [
                'en' => ['name' => 'High-load cartridge heater — 1,000 W'],
                'es' => ['name' => 'Resistencia cartucho de alta carga — 1.000 W'],
            ],
            'resistencia-cartucho-com-termopar-630w' => [
                'en' => ['name' => 'Cartridge heater with thermocouple — 630 W'],
                'es' => ['name' => 'Resistencia cartucho con termopar — 630 W'],
            ],
            'resistencia-imersao-flangeada-9kw' => [
                'en' => ['name' => 'Flanged immersion heater — 9 kW'],
                'es' => ['name' => 'Resistencia de inmersión bridada — 9 kW'],
            ],
            'resistencia-imersao-rosqueada-3kw' => [
                'en' => ['name' => 'Threaded immersion heater — 3 kW'],
                'es' => ['name' => 'Resistencia de inmersión roscada — 3 kW'],
            ],
            'termopar-tipo-k' => [
                'en' => ['name' => 'Type K thermocouple with standard connector'],
                'es' => ['name' => 'Termopar tipo K con conector estándar'],
            ],
            'controlador-de-temperatura-pid' => [
                'en' => ['name' => 'PID temperature controller'],
                'es' => ['name' => 'Controlador de temperatura PID'],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function categoryTranslations(): array
    {
        return [
            'tubulares' => [
                'en' => ['name' => 'Tubular heaters'],
                'es' => ['name' => 'Resistencias tubulares'],
            ],
            'cartucho' => [
                'en' => ['name' => 'Cartridge heaters'],
                'es' => ['name' => 'Resistencias cartucho'],
            ],
            'imersao' => [
                'en' => ['name' => 'Immersion heaters'],
                'es' => ['name' => 'Resistencias de inmersión'],
            ],
            'acessorios' => [
                'en' => ['name' => 'Thermocouples and accessories'],
                'es' => ['name' => 'Termopares y accesorios'],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function postTranslations(): array
    {
        return [
            'por-que-a-resistencia-queima-antes-do-prazo' => [
                'en' => [
                    'title' => 'Why heating elements burn out early',
                    'excerpt' => 'Most of the time the part did not fail alone: the problem is load, airflow or the terminal.',
                ],
                'es' => [
                    'title' => 'Por qué la resistencia se quema antes de tiempo',
                    'excerpt' => 'En la mayoría de los casos la pieza no falló sola: el problema está en la carga, la ventilación o el terminal.',
                ],
            ],
            'cartucho-ou-tubular-como-escolher' => [
                'en' => [
                    'title' => 'Cartridge or tubular: how to choose',
                    'excerpt' => 'The housing geometry decides more than the power rating. See when each type makes sense.',
                ],
                'es' => [
                    'title' => 'Cartucho o tubular: cómo elegir',
                    'excerpt' => 'La geometría del alojamiento decide más que la potencia. Ve cuándo cada tipo tiene sentido.',
                ],
            ],
            'aquecimento-de-tanques-potencia-sem-chute' => [
                'en' => [
                    'title' => 'Tank heating: power without guesswork',
                    'excerpt' => 'Mass, temperature delta and time define the power. The rest is heat loss — and it cannot be ignored.',
                ],
                'es' => [
                    'title' => 'Calentamiento de tanques: potencia sin adivinar',
                    'excerpt' => 'Masa, delta de temperatura y tiempo definen la potencia. El resto es pérdida térmica — y no se puede ignorar.',
                ],
            ],
            'o-que-enviar-para-um-orcamento-de-reposicao' => [
                'en' => [
                    'title' => 'What to send for a replacement quote',
                    'excerpt' => 'Four details are enough to leave generic quotes behind and get a part that fits.',
                ],
                'es' => [
                    'title' => 'Qué enviar para un presupuesto de reposición',
                    'excerpt' => 'Cuatro datos bastan para salir del presupuesto genérico y recibir una pieza que encaje.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function faqTranslations(): array
    {
        return [
            'Vocês fabricam sob medida?' => [
                'en' => [
                    'question' => 'Do you manufacture custom parts?',
                    'answer' => 'Yes. Most of our production is built from a drawing or sample. Send the part, a photo with measurements or the technical drawing and we reply with a proposal defining geometry, material and power.',
                ],
                'es' => [
                    'question' => '¿Fabrican a medida?',
                    'answer' => 'Sí. La mayor parte de la producción es por plano o muestra. Envía la pieza, la foto con medidas o el plano técnico y devolvemos una propuesta con geometría, material y potencia definidos.',
                ],
            ],
            'Qual é o prazo de entrega?' => [
                'en' => [
                    'question' => 'What is the delivery time?',
                    'answer' => 'Standard parts ship within 10 business days. Custom parts take 15 to 25 business days after drawing approval. Emergency replacements can be assessed case by case.',
                ],
                'es' => [
                    'question' => '¿Cuál es el plazo de entrega?',
                    'answer' => 'Las piezas de línea salen en hasta 10 días hábiles. A medida, el plazo varía de 15 a 25 días hábiles tras la aprobación del plano. La reposición de emergencia se evalúa caso por caso.',
                ],
            ],
            'Como enviar um pedido de reposição?' => [
                'en' => [
                    'question' => 'How do I send a replacement request?',
                    'answer' => 'The fastest way is the contact form, with a photo of the part, housing measurements, voltage and working temperature. We also take requests by phone and WhatsApp during business hours.',
                ],
                'es' => [
                    'question' => '¿Cómo enviar un pedido de reposición?',
                    'answer' => 'El camino más rápido es el formulario de contacto, con foto de la pieza, medidas del alojamiento, tensión y temperatura de trabajo. También atendemos por teléfono y WhatsApp en horario comercial.',
                ],
            ],
            'Vocês emitem laudo dos ensaios?' => [
                'en' => [
                    'question' => 'Do you issue test reports?',
                    'answer' => 'Yes. Every batch ships with cold resistance measurement and insulation testing. For critical applications we issue a report signed by the responsible engineer.',
                ],
                'es' => [
                    'question' => '¿Emiten informe de los ensayos?',
                    'answer' => 'Sí. Todo lote sale con medición de resistencia en frío y prueba de aislamiento. Para aplicaciones críticas emitimos informe firmado por el ingeniero responsable.',
                ],
            ],
            'Qual é a garantia?' => [
                'en' => [
                    'question' => 'What is the warranty?',
                    'answer' => 'Twelve months against manufacturing defects, provided the part is used under the conditions stated in the order. The warranty does not cover failure from wrong voltage, lack of airflow or a loose terminal.',
                ],
                'es' => [
                    'question' => '¿Cuál es la garantía?',
                    'answer' => 'Doce meses contra defecto de fabricación, siempre que la pieza se use en la condición informada en el pedido. La garantía no cubre fallas por tensión incorrecta, falta de ventilación o terminal mal apretado.',
                ],
            ],
            'Atendem fora do Rio Grande do Sul?' => [
                'en' => [
                    'question' => 'Do you ship outside Rio Grande do Sul?',
                    'answer' => 'We ship across Brazil by carrier. For long parts we use rigid packaging and send the tracking receipt on the same day.',
                ],
                'es' => [
                    'question' => '¿Atienden fuera de Rio Grande do Sul?',
                    'answer' => 'Atendemos todo Brasil por transportadora. Para piezas largas usamos embalaje rígido y enviamos el comprobante de envío el mismo día.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    private function testimonialTranslations(): array
    {
        return [
            'Ricardo Basso' => [
                'en' => [
                    'role' => 'Maintenance supervisor · Metalúrgica Andrade',
                    'quote' => 'We compared three suppliers. Termoflux was the only one that asked for the furnace drawing before quoting. The element arrived with the right curve and installed without rework.',
                ],
                'es' => [
                    'role' => 'Supervisor de mantenimiento · Metalúrgica Andrade',
                    'quote' => 'Comparamos con tres proveedores. Termoflux fue el único que pidió el plano del horno antes de enviar precio. La resistencia llegó con la curva correcta y se instaló sin retrabajo.',
                ],
            ],
            'Cláudia Menezes' => [
                'en' => [
                    'role' => 'Process engineer · Química Sul',
                    'quote' => 'The immersion heater arrived on time and with the insulation report. We installed it straight away, without stopping the tank for a whole day as before.',
                ],
                'es' => [
                    'role' => 'Ingeniera de procesos · Química Sul',
                    'quote' => 'La resistencia de inmersión llegó en plazo y con el informe de aislamiento. La instalamos directo, sin parar el tanque un día entero como antes.',
                ],
            ],
            'Fábio Klein' => [
                'en' => [
                    'role' => 'Production supervisor · Plásticos Vale',
                    'quote' => 'They have been supplying the same cartridge for six years. When we need a variation, the drawing comes back in two days and the part in two weeks.',
                ],
                'es' => [
                    'role' => 'Supervisor de producción · Plásticos Vale',
                    'quote' => 'Reponen el mismo cartucho desde hace seis años. Cuando necesitamos una variación, el plano vuelve en dos días y la pieza en dos semanas.',
                ],
            ],
        ];
    }
}
