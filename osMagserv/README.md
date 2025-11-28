# 🛠️ osMagserv - Sistema de Gestão de Ordens de Serviço

O **osMagserv** é um sistema web desenvolvido em Laravel para gerenciamento completo de manutenções, ordens de serviço, clientes e controle financeiro. O projeto visa otimizar o fluxo de trabalho de prestadores de serviços, centralizando orçamentos, agendamentos e histórico de atividades.

## 🚀 Funcionalidades Principais

O sistema é dividido em módulos integrados:

### 📊 Dashboard
- Visão geral financeira (Receitas vs. Despesas).
- Status de manutenções (Preventivas e Corretivas) no mês atual.
- Gráficos de fluxo de caixa diário.
- Atividades recentes registradas automaticamente pelo sistema.

### 📱 Integrações e Automação
- **Evolution API (WhatsApp):** Integração para automação de atendimento.
  - **Solicitação de Orçamento:** Clientes podem solicitar orçamentos via chat, que são registrados automaticamente no sistema.
  - **Manutenção Corretiva:** Abertura de chamados de emergência/corretiva diretamente pela API, caindo na fila de "Pendentes" para aprovação.

### 🔧 Gestão de Manutenção
- **Preventiva e Corretiva:** Cadastro detalhado de serviços.
- **Status:** Controle de fluxo (Pendente, Agendada, Em Andamento, Concluída, Cancelada).
- **Anexos:** Upload de arquivos e imagens relacionados à manutenção.
- **Histórico:** Monitoramento automático de alterações via *Observers*.

### 💰 Financeiro
- **Contas a Pagar e Receber:** Controle de vencimentos e pagamentos.
- **Orçamentos:** Criação e gerenciamento de orçamentos para clientes.

### 👥 Cadastros
- **Clientes:** Gestão de dados de clientes (Pessoa Física/Jurídica).
- **Processos:** Acompanhamento de processos internos.

### 🔔 Notificações e Logs
- **Activity Log:** O sistema registra em tempo real ("Timeline") quando uma solicitação chega via API ou quando um usuário altera o status de uma manutenção.

---

## 💻 Tecnologias Utilizadas

- **Backend:** [Laravel 11](https://laravel.com/) (PHP)
- **Frontend:** Blade Templates, JavaScript (Vanilla), Bootstrap
- **Banco de Dados:** MySQL
- **Integração Externa:** Evolution API (WhatsApp Gateway)
- **Build Tool:** Vite

