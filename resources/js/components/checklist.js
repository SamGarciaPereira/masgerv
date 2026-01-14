let tasks = [];
let checklistInput, container, newTaskInput;

function renderTasks() {
    container.innerHTML = "";

    tasks.forEach((task, index) => {
        const li = document.createElement("li");
        li.className =
            "flex items-center justify-between p-3 bg-gray-50 rounded-lg border " +
            (task.completed
                ? "border-green-200 bg-green-50"
                : "border-gray-200");

        // Lado Esquerdo: Checkbox + Texto
        const leftDiv = document.createElement("div");
        leftDiv.className = "flex items-center gap-3";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = task.completed;
        checkbox.className =
            "w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer";

        // Evento de marcar/desmarcar
        checkbox.addEventListener("change", () => {
            task.completed = !task.completed;
            updateState();
        });

        const span = document.createElement("span");
        span.textContent = task.text;
        // Efeito de riscado (line-through)
        span.className = task.completed
            ? "text-gray-400 line-through decoration-2"
            : "text-gray-700";

        leftDiv.appendChild(checkbox);
        leftDiv.appendChild(span);

        // Botão Excluir
        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
        deleteBtn.className = "text-red-500 hover:text-red-700 px-2";

        // Evento de excluir
        deleteBtn.addEventListener("click", () => {
            tasks.splice(index, 1);
            updateState();
        });

        li.appendChild(leftDiv);
        li.appendChild(deleteBtn);
        container.appendChild(li);
    });
}

function addTask() {
    const text = newTaskInput.value.trim();
    if (text) {
        tasks.push({ text: text, completed: false });
        newTaskInput.value = "";
        updateState();
    }
}

// Atualiza o input hidden e re-renderiza a lista
function updateState() {
    checklistInput.value = JSON.stringify(tasks);
    renderTasks();
}

document.addEventListener("DOMContentLoaded", function () {
    checklistInput = document.getElementById("checklist_data");
    container = document.getElementById("checklist-container");
    newTaskInput = document.getElementById("new-task-input");
    const addTaskBtn = document.querySelector('button[onclick="addTask()"]');

    // Verifica se os elementos existem na página antes de rodar (evita erros em outras telas)
    if (!checklistInput || !container) return;

    // Inicializa as tarefas lendo o JSON que veio do PHP no input hidden
    try {
        tasks = JSON.parse(checklistInput.value || "[]");
    } catch (e) {
        console.error("Erro ao ler checklist:", e);
        tasks = [];
    }

    // Listeners para adicionar tarefas
    if (addTaskBtn) {
        addTaskBtn.addEventListener("click", addTask);
    }

    // Permitir adicionar apertando Enter
    if (newTaskInput) {
        newTaskInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault(); // Impede o formulário principal de ser enviado
                addTask();
            }
        });
    }

    // Primeira renderização ao carregar a página
    renderTasks();
});
