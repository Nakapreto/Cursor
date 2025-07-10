# Super Links Multisite

Plugin de encurtamento de links otimizado para WordPress Multisite configurado com subdomínios.

## O que foi modificado

Esta é uma versão modificada do plugin "Super Links Light" que foi adaptada especificamente para funcionar com WordPress Multisite sem sistema de ativação obrigatório.

### Principais mudanças:

1. **Removido Sistema de Ativação**: 
   - Eliminado completamente o sistema de ativação por telefone
   - O plugin agora está sempre ativo e pronto para uso
   - Removidos os arquivos de view relacionados à ativação

2. **Adaptado para WordPress Multisite**:
   - Adicionado `Network: true` no cabeçalho do plugin
   - Otimizado para funcionar com configuração de subdomínios
   - Compatível com redes multisite

3. **Interface Simplificada**:
   - Nova tela inicial focada nas funcionalidades do plugin
   - Removidas referências desnecessárias à versão Pro
   - Interface mais limpa e direta

4. **Arquivos Removidos**:
   - `application/views/admin/activation.php`
   - `application/views/admin/activated.php`
   - `application/views/admin/notActivated.php`
   - `application/helpers/plugin-update-checker/` (pasta completa)

5. **Código Limpo**:
   - Removidos métodos relacionados à validação de telefone
   - Simplificado o método `isPluginActive()` para sempre retornar `true`
   - Removidos hooks e controllers de ativação

## Instalação

1. Faça o upload da pasta `super-links-multisite` para o diretório `/wp-content/plugins/`
2. Ative o plugin na rede (Network Admin > Plugins)
3. Configure o plugin conforme necessário

## Funcionalidades

- ✅ Encurtador de links (Redirecionamento PHP)
- ✅ Encurtador de links para Whatsapp e Telegram
- ✅ Categorização de links
- ✅ Testes A/B
- ✅ Importar links de outros plugins
- ✅ Compatível com WordPress Multisite
- ✅ Suporte a subdomínios
- ✅ Sem sistema de ativação obrigatório

## Compatibilidade

- WordPress 5.0+
- WordPress Multisite
- PHP 7.4+
- Configuração com subdomínios

## Suporte

Este é um plugin modificado sem suporte oficial. Use por sua conta e risco.

## Licença

GPL-2.0+ (mesma licença do plugin original)