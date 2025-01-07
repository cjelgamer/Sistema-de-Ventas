<template>
  <div class="calculator-notes-container">
    <div class="calculator-section">
      <div class="calculator">
        <div class="calculation-display">
          <div class="operation">{{ currentOperation || '&nbsp;' }}</div>
          <div class="output">
            <span class="result">{{ display }}</span>
          </div>
        </div>
        <div class="buttons">
          <button @click="clear" class="bg-red">AC</button>
          <button @click="backspace" class="operation-btn">⌫</button>
          <button @click="percentage" class="operation-btn">%</button>
          <button @click="appendOperator('/')" class="operation-btn">÷</button>
          <button @click="appendNumber('7')">7</button>
          <button @click="appendNumber('8')">8</button>
          <button @click="appendNumber('9')">9</button>
          <button @click="appendOperator('*')" class="operation-btn">×</button>
          <button @click="appendNumber('4')">4</button>
          <button @click="appendNumber('5')">5</button>
          <button @click="appendNumber('6')">6</button>
          <button @click="appendOperator('-')" class="operation-btn">−</button>
          <button @click="appendNumber('1')">1</button>
          <button @click="appendNumber('2')">2</button>
          <button @click="appendNumber('3')">3</button>
          <button @click="appendOperator('+')" class="operation-btn">+</button>
          <button @click="appendNumber('0')" class="span-2">0</button>
          <button @click="appendDecimal">.</button>
          <button @click="calculate" class="bg-green">=</button>
        </div>
      </div>
    </div>

    <div class="notes-section">
      <h3>Notas Diarias</h3>
      <div class="notes-list" v-if="savedNotes.length > 0">
        <div v-for="(note, index) in savedNotes" :key="index" class="note-item">
          <div class="note-content" v-if="editingIndex !== index">
            {{ note.content }}
            <div class="note-date">{{ formatDate(note.date) }}</div>
          </div>
          <textarea
            v-else
            v-model="editingContent"
            class="edit-note-input"
            @keyup.enter="saveEdit"
          ></textarea>
          <div class="note-actions">
            <button 
              v-if="editingIndex !== index" 
              @click="editNote(index)" 
              class="edit-btn"
            >
              Editar
            </button>
            <button 
              v-else 
              @click="saveEdit" 
              class="save-btn"
            >
              Guardar
            </button>
            <button 
              @click="deleteNote(index)" 
              class="delete-btn"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
      <textarea 
        v-model="newNote"
        placeholder="Escribe una nueva nota aquí..."
        class="daily-note-input"
        rows="4"
      ></textarea>
      <button @click="saveNewNote" class="save-daily-note-btn">
        Agregar Nota
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Calculator',
  data() {
    return {
      display: '0',
      currentOperation: '',
      previousValue: null,
      operator: null,
      clearOnNext: false,
      lastNote: '',
      newNote: '',
      savedNotes: JSON.parse(localStorage.getItem('savedNotes') || '[]'),
      editingIndex: -1,
      editingContent: '',
      hasDecimal: false
    }
  },
  mounted() {
    window.addEventListener('keydown', this.handleKeyPress);
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.handleKeyPress);
  },
  methods: {
    handleKeyPress(event) {
      if (this.isCalculatorKey(event.key)) {
        event.preventDefault();
      }

      if (/[0-9]/.test(event.key)) {
        this.appendNumber(event.key);
      }
      else if (['+', '-', '*', '/'].includes(event.key)) {
        this.appendOperator(event.key);
      }
      else if (event.key === 'Enter' || event.key === '=') {
        this.calculate();
      }
      else if (event.key === 'Escape') {
        this.clear();
      }
      else if (event.key === 'Backspace') {
        this.backspace();
      }
      else if (event.key === '.') {
        this.appendDecimal();
      }
    },
    isCalculatorKey(key) {
      return /[0-9\+\-\*\/=\.]/.test(key) || 
             key === 'Enter' || 
             key === 'Escape' ||
             key === 'Backspace';
    },
    appendNumber(num) {
      if (this.clearOnNext) {
        this.display = num;
        this.clearOnNext = false;
      } else {
        this.display = this.display === '0' ? num : this.display + num;
      }
    },
    appendDecimal() {
      if (!this.display.includes('.')) {
        this.display += '.';
      }
    },
    appendOperator(op) {
      this.calculate();
      this.previousValue = parseFloat(this.display);
      this.operator = op;
      this.currentOperation = `${this.previousValue} ${this.getOperatorSymbol(op)}`;
      this.clearOnNext = true;
      this.hasDecimal = false;
    },
    getOperatorSymbol(op) {
      const symbols = {
        '+': '+',
        '-': '−',
        '*': '×',
        '/': '÷'
      };
      return symbols[op] || op;
    },
    backspace() {
      if (this.display.length > 1) {
        this.display = this.display.slice(0, -1);
      } else {
        this.display = '0';
      }
    },
    percentage() {
      this.display = (parseFloat(this.display) / 100).toString();
    },
    calculate() {
      if (this.operator === null || this.clearOnNext) {
        return;
      }

      const currentValue = parseFloat(this.display);
      let result;

      switch (this.operator) {
        case '+':
          result = this.previousValue + currentValue;
          break;
        case '-':
          result = this.previousValue - currentValue;
          break;
        case '*':
          result = this.previousValue * currentValue;
          break;
        case '/':
          if (currentValue === 0) {
            this.display = 'Error';
            return;
          }
          result = this.previousValue / currentValue;
          break;
      }

      this.currentOperation = `${this.previousValue} ${this.getOperatorSymbol(this.operator)} ${currentValue} = ${result}`;
      this.display = result.toString();
      this.operator = null;
      this.clearOnNext = true;
    },
    clear() {
      this.display = '0';
      this.previousValue = null;
      this.operator = null;
      this.clearOnNext = false;
      this.currentOperation = '';
      this.hasDecimal = false;
    },
    saveNewNote() {
      if (this.newNote.trim()) {
        this.savedNotes.unshift({
          content: this.newNote,
          date: new Date().toISOString()
        });
        localStorage.setItem('savedNotes', JSON.stringify(this.savedNotes));
        this.newNote = '';
      }
    },
    editNote(index) {
      this.editingIndex = index;
      this.editingContent = this.savedNotes[index].content;
    },
    saveEdit() {
      if (this.editingContent.trim() && this.editingIndex !== -1) {
        this.savedNotes[this.editingIndex].content = this.editingContent;
        this.savedNotes[this.editingIndex].date = new Date().toISOString();
        localStorage.setItem('savedNotes', JSON.stringify(this.savedNotes));
        this.editingIndex = -1;
        this.editingContent = '';
      }
    },
    deleteNote(index) {
      this.savedNotes.splice(index, 1);
      localStorage.setItem('savedNotes', JSON.stringify(this.savedNotes));
    },
    formatDate(dateString) {
      const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      return new Date(dateString).toLocaleDateString('es-ES', options);
    }
  }
}
</script>

<style scoped>
.calculator-notes-container {
  display: flex;
  gap: 20px;
  padding: 20px;
  max-width: 900px;
  margin: 0 auto;
}

.calculator-section {
  flex: 0 0 auto;
}

.calculator {
  width: 320px;
  background: #fff;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 10px 20px rgba(166, 33, 3, 0.1);
  border: 1px solid rgba(166, 33, 3, 0.1);
}

.calculation-display {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 20px;
  box-shadow: inset 0 2px 4px rgba(166, 33, 3, 0.05);
  border: 1px solid rgba(166, 33, 3, 0.1);
}

.operation {
  font-size: 14px;
  color: #0047AB;
  min-height: 20px;
  text-align: right;
  margin-bottom: 5px;
}

.output {
  text-align: right;
  min-height: 40px;
}

.result {
  font-size: 32px;
  font-weight: 500;
  color: #2d3748;
}

.buttons {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}

.buttons button {
  border: none;
  border-radius: 12px;
  padding: 15px;
  font-size: 20px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  background: #fff;
  color: #2d3748;
  box-shadow: 0 4px 6px rgba(166, 33, 3, 0.1);
  border: 1px solid rgba(166, 33, 3, 0.1);
}

.buttons button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(166, 33, 3, 0.15);
  border-color: rgba(166, 33, 3, 0.2);
}

.buttons button:active {
  transform: translateY(1px);
  box-shadow: 0 2px 4px rgba(166, 33, 3, 0.1);
}

.span-2 {
  grid-column: span 2;
}

.operation-btn {
  color: #0047AB !important;
  font-weight: 600 !important;
  background: #fff !important;
}

.operation-btn:hover {
  background: rgba(166, 33, 3, 0.05) !important;
}

.bg-red {
  background: #0047AB !important;
  color: white !important;
}

.bg-red:hover {
  background: #074cac !important;
}

.bg-green {
  background: #F2C744 !important;
  color: #2d3748 !important;
  font-weight: 600 !important;
}

.bg-green:hover {
  background: #e0b83c !important;
}

.notes-section {
  flex: 1;
  background: #fff;
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 20px rgba(166, 33, 3, 0.1);
  border: 1px solid rgba(166, 33, 3, 0.1);
}

.notes-section h3 {
  color: #0047AB;
  margin-bottom: 20px;
  font-size: 1.5rem;
  font-weight: 600;
  border-bottom: 2px solid #0047AB;
  padding-bottom: 8px;
}

.daily-note-input {
  width: 100%;
  padding: 15px;
  border: 2px solid rgba(166, 33, 3, 0.1);
  border-radius: 12px;
  background: #f8f9fa;
  font-size: 16px;
  line-height: 1.5;
  resize: vertical;
  min-height: 100px;
  margin-bottom: 15px;
  transition: all 0.2s ease;
}

.daily-note-input:focus {
  outline: none;
  border-color: #0047AB;
  box-shadow: 0 0 0 3px rgba(82, 162, 232, 0.2);
  background: #fff;
}

.notes-list {
  margin-bottom: 20px;
  max-height: 300px;
  overflow-y: auto;
  padding-right: 10px;
}

.note-item {
  background: #fff;
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 10px;
  box-shadow: 0 2px 4px rgba(166, 33, 3, 0.05);
  border: 1px solid rgba(166, 33, 3, 0.1);
  transition: all 0.3s ease;
}

.note-item:hover {
  box-shadow: 0 4px 8px rgba(166, 33, 3, 0.1);
  transform: translateY(-1px);
}

.note-content {
  margin-bottom: 10px;
  white-space: pre-wrap;
  color: #2d3748;
}

.note-date {
  font-size: 12px;
  color: #0047AB;
  margin-top: 5px;
  opacity: 0.8;
}

.note-actions {
  display: flex;
  gap: 8px;
  margin-top: 10px;
}

.edit-btn, .save-btn, .delete-btn {
  padding: 8px 16px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 14px;
  box-shadow: 0 2px 4px rgba(166, 33, 3, 0.1);
}

.edit-btn {
  background: #F2C744;
  color: #2d3748;
  flex: 1;
}

.edit-btn:hover {
  background: #e0b83c;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(166, 33, 3, 0.15);
}

.save-btn {
  background: #F2C744;
  color: #2d3748;
  flex: 1;
}

.save-btn:hover {
  background: #e0b83c;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(166, 33, 3, 0.15);
}

.delete-btn {
  background: #0047AB;
  color: white;
  flex: 1;
}

.delete-btn:hover {
  background: #0047AB;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(166, 33, 3, 0.15);
}

.edit-note-input {
  width: 100%;
  padding: 12px;
  border: 2px solid #F2C744;
  border-radius: 8px;
  margin-bottom: 10px;
  font-size: 14px;
  resize: vertical;
  background: #fff;
  transition: all 0.3s ease;
}

.edit-note-input:focus {
  outline: none;
  border-color: #0047AB;
  box-shadow: 0 0 0 3px rgba(166, 33, 3, 0.1);
}

.save-daily-note-btn {
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 12px;
  background: #0047AB;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(166, 33, 3, 0.1);
}

.save-daily-note-btn:hover {
  background: #034095;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(166, 33, 3, 0.2);
}

.notes-list::-webkit-scrollbar {
  width: 8px;
}

.notes-list::-webkit-scrollbar-track {
  background: rgba(166, 33, 3, 0.05);
  border-radius: 10px;
}

.notes-list::-webkit-scrollbar-thumb {
  background: rgba(166, 33, 3, 0.2);
  border-radius: 10px;
}

.notes-list::-webkit-scrollbar-thumb:hover {
  background: rgba(166, 33, 3, 0.3);
}

@media (max-width: 768px) {
  .calculator-notes-container {
    flex-direction: column;
  }
  
  .calculator-section, .notes-section {
    width: 100%;
  }

  .calculator {
    margin: 0 auto;
  }

  .buttons button {
    padding: 12px;
    font-size: 18px;
  }

  .notes-list {
    max-height: 250px;
  }
}
</style>