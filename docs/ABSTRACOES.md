# Abstrações do Webstract (guia para devs)

Este documento mapeia as principais **abstrações (interfaces, classes abstratas e traits)** do projeto para facilitar manutenção, extensão e onboarding.

## Visão geral por domínio

### 1) Runner / DI
- `Webstract\Runner\Runner` (abstrata): base para bootstrap de execução com container DI.
  - Método-chave: `withBinds(Bind ...$bind)` para montar definições.
  - Extensão típica: criar uma classe concreta e implementar `execute()`.
- `Webstract\Runner\Bind`: representa o mapeamento interface → implementação/instância.

### 2) HTTP / Controller
- `Webstract\Controller\Controller` (interface): contrato PSR-15 para controladores.
  - Exige `handle()` e `middlewares()`.
- Abstratas de alto nível:
  - `ActionController`
  - `ApiController`
  - `PageController`
  - `AsyncComponentController`
  - `DownloadableActionController` (especialização de action)
  - `DownloadableApiController` (especialização de API)
- Traits reutilizáveis de resposta:
  - `RedirectableResponse`
  - `AsyncRedirectableResponse`
  - `DownloadableResponse`

### 3) Roteamento
- `Webstract\Route\RouteHandleable` (interface): define o manuseio da rota.
- `Webstract\Route\RouteDefinition` (interface): contrato de definição estática de rota.
  - `getMethod(): RequestMethod`
  - `getPattern(): string`
- `Webstract\Route\RouteResolver` (interface): resolve rota a partir da request/entrada.
- `Webstract\Route\RouteProviderInterface` (interface): provê rotas para o app.
- `Webstract\Route\RouterOutputProvider` (abstrata): base para construção de saída de roteamento.
- `Webstract\Route\RoutePathTemplate` (abstrata): base para templates de path.

### 4) Request pipeline
- `Webstract\Request\SafeRequestHandlerServerErrorControllerProvider` (abstrata): ponto de extensão para fallback de erro em cadeia de request handling.

### 5) Web (páginas/componentes)
- `Webstract\Web\Content` (abstrata): conteúdo renderizável.
- `Webstract\Web\Component` (abstrata): componente base.
- `Webstract\Web\AsyncComponent` (abstrata): componente assíncrono (especialização).
- `Webstract\Web\Page` (abstrata): página base do domínio Web.

### 6) Template Engine
- `Webstract\TemplateEngine\TemplateEngineRenderer` (interface): contrato de renderização.
- `Webstract\TemplateEngine\TwigTemplateEngine`: implementação concreta baseada em Twig.

### 7) Ambiente (Env)
- `Webstract\Env\EnvironmentVarInterface`: representa uma variável de ambiente tipada/encapsulada.
- `Webstract\Env\EnvironmentVarLoaderInterface`: contrato para carregamento de env vars.
- `Webstract\Env\EnvironmentHandlerInterface`: acesso centralizado de variáveis.
  - `getVar()` lança exceção quando não resolvida.
  - `getVarOrDefault()` permite fallback.
- Visitors de env por domínio:
  - `ApplicationEnvironmentVarVisitor`
  - `DatabaseEnvironmentVarVisitor`
  - `FileStorageEnvironmentVarVisitor`
  - `LogEnvironmentVarVisitor`

### 8) Database
- `Webstract\Database\Repository` (interface): contrato de repositório.
- `Webstract\Database\DatabaseRepositoryConnector` (interface): fábrica/conector para repositórios.
- `Webstract\Database\DatabaseTransactionManager` (interface): abstração de transação.
- Concretas auxiliares:
  - `DatabasePdoConnector`

### 9) Session
- `Webstract\Session\SessionHandler` (interface): operação base de sessão.
- `Webstract\Session\KeyValueSessionHandler` (interface): sessão orientada a chave/valor.
- `Webstract\Session\SessionKeyInterface` (interface): chave tipada de sessão.
- Visitors:
  - `UserSessionVisitor`

### 10) Storage
- `Webstract\Storage\FileHandler` (interface): manipulação de arquivo.
- `Webstract\Storage\Client\Client` (interface): cliente de storage remoto/local.
- `Webstract\Storage\Object\FileObject` (abstrata): objeto de arquivo no domínio de storage.

### 11) Logging
- `Webstract\Log\Collector\LogCollector` (interface): coleta/acúmulo de logs.

### 12) RBAC
- `Webstract\Rbac\PermissionProvider` (interface): provedor de permissões.

### 13) CLI
- `Webstract\Cli\Command` (abstrata): comando base.
- `Webstract\Cli\CommandProviderInterface`: contrato para prover comandos.
- `Webstract\Cli\CommandProvider`: registrador/resolvedor de comandos.

### 14) PDF
- `Webstract\Pdf\PdfContent` (abstrata): conteúdo para geração de PDF.
- `Webstract\Pdf\PdfGenerator` (interface): gerador de PDF.

---

## Padrões de extensão recomendados

1. **Programar por interface**
   - Preferir depender de contratos (`*Interface`) nos construtores.
   - Fazer bind no `Runner` via `Bind`.

2. **Criar especializações por domínio**
   - Para novos fluxos HTTP, estender uma base (`ApiController`, `PageController`, etc.) antes de criar classe “do zero”.

3. **Compor comportamento com traits de resposta**
   - Reaproveitar traits de redirect/download para evitar duplicação.

4. **Separar resolução de rota de execução**
   - Definições de rota (`RouteDefinition`) devem focar em método/pattern; lógica de execução fica nos handlers/controllers.

5. **Centralizar acesso a env/session/storage por contratos**
   - Facilita testes e troca de implementação (ex.: storage local → S3).

---

## Onde começar ao adicionar uma feature

- Endpoint novo:
  1. Definir rota (`RouteDefinition`/provider)
  2. Implementar controller concreto a partir da abstrata adequada
  3. Declarar binds no runner/bootstrap

- Nova integração externa:
  1. Criar interface no domínio apropriado
  2. Criar implementação concreta
  3. Injetar por DI via `Bind`

- Novo mecanismo de infra (log/storage/env):
  1. Implementar contrato existente
  2. Trocar bind no bootstrap sem alterar código consumidor

---

## Observação

Este documento é um mapa arquitetural. Para detalhes comportamentais, consulte os testes em `test/` e implementações concretas em `src/`.
