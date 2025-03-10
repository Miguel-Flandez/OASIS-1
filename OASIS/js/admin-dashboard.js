// function updateDateTime() {
//     const now = new Date();

//     const day = String(now.getDate()).padStart(2, '0'); 
//     const month = String(now.getMonth() + 1).padStart(2, '0'); 
//     const year = now.getFullYear(); 

//     const hours = String(now.getHours()).padStart(2, '0'); 
//     const minutes = String(now.getMinutes()).padStart(2, '0'); 
//     const seconds = String(now.getSeconds()).padStart(2, '0'); 

//     const formattedDateTime = `${day}/${month}/${year} | ${hours}:${minutes}:${seconds}`;

//     document.getElementById('date-and-time').textContent = formattedDateTime;

// }

// // Update every second
// setInterval(updateDateTime, 1000);

// // Run it once on load
// updateDateTime();

// const dropdown = document.getElementById('data-analytics');
// const dashboard = document.getElementById('dashboard-info');
// const data = document.getElementById('graph-container');

// data.classList.add('hide');

// dropdown.addEventListener('change', function() {
//     if (dropdown.value === 'statistics') {
//         dashboard.classList.remove('hide');
//         data.classList.add('hide');
//     } else if (dropdown.value === 'payment-trends') {
//         data.classList.remove('hide');
//         dashboard.classList.add('hide');
//     }
// });

const chat = document.querySelector('.fa-solid.fa-comment-dots');
const chatbox = document.querySelector('.chatbox');

chat.onclick = function(){
    if(chatbox.classList.contains('hide')){
        chatbox.classList.remove('hide');
    }else{
        chatbox.classList.add('hide');
    }
}


const supabaseUrl = 'https://shakptjwjjoidytkbdjg.supabase.co';
const tableName = 'OASIS';
const apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InNoYWtwdGp3ampvaWR5dGtiZGpnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDE1ODM1NTksImV4cCI6MjA1NzE1OTU1OX0.Dsk8U7m3oj-QS5ZOMM6rOzyf-jqoNb_6qp1S1aVBbZ8'; // shortened for clarity

async function fetchPaymentData() {
  const response = await fetch(`${supabaseUrl}/rest/v1/${tableName}`, {
    headers: {
      'apikey': apiKey,
      'Authorization': `Bearer ${apiKey}`,
      'Content-Type': 'application/json'
    }
  });

  const data = await response.json();
  console.log('Fetched data:', data);

  const methodCounts = {};
  data.forEach(entry => {
    const method = entry['payment-method'] || entry['payment_method'];
    if (method) {
      methodCounts[method] = (methodCounts[method] || 0) + 1;
    }
  });

  return { methodCounts, data };
}

async function drawCharts() {
  const { methodCounts, data } = await fetchPaymentData();

  // Pie Chart for Payment Methods
  const labels = Object.keys(methodCounts);
  const counts = Object.values(methodCounts);

  const ctx1 = document.getElementById('paymentChart').getContext('2d');
  new Chart(ctx1, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        label: 'Payment Methods',
        data: counts,
        backgroundColor: [
          '#1E90FF', // DodgerBlue
          '#4682B4', // SteelBlue
          '#5F9EA0', // CadetBlue
          '#6495ED', // CornflowerBlue
          '#87CEFA', // LightSkyBlue
          '#ADD8E6'  // LightBlue
        ]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
        title: { display: true, text: 'Payment Method Distribution' },
        datalabels: {
          color: '#fff',
          formatter: (value, context) => {
            let total = context.chart._metasets[0].total;
            let percentage = (value / total * 100).toFixed(1) + '%';
            return percentage;
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });
  


  // Line Chart for Payment Dates
  const sortedData = data
    .filter(entry => entry.payment_dates)
    .sort((a, b) => new Date(a.payment_dates) - new Date(b.payment_dates));

  const dateLabels = sortedData.map(entry => entry.payment_dates);
  const dateCounts = sortedData.map((_, index) => index + 1); // simple cumulative count

  const ctx2 = document.getElementById('paymentDateChart').getContext('2d');

  function processGroupedData(data) {
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const grouped = {};

    data.forEach(entry => {
        const date = new Date(entry['payment_date'] || entry['payment-date']);
        const month = monthNames[date.getMonth()]; // e.g., "March"
        const method = entry['payment_method'] || entry['payment-method'];

        if (!grouped[month]) grouped[month] = {};
        if (!grouped[month][method]) grouped[month][method] = 0;

        grouped[month][method]++;
    });

    const months = Object.keys(grouped).sort((a, b) => monthNames.indexOf(a) - monthNames.indexOf(b));

    const methods = ['gcash', 'bpi', 'paymaya']; // dynamically fetch if needed

    const datasets = methods.map(method => ({
        label: method,
        data: months.map(month => grouped[month]?.[method] || 0),
        backgroundColor: getColorForMethod(method)
    }));

    return { months, datasets };
}

  
  function getColorForMethod(method) {
    switch (method) {
      case 'gcash': return '#1E90FF';    // DodgerBlue
      case 'bpi': return '#4682B4';      // SteelBlue
      case 'paymaya': return '#87CEFA';  // LightSkyBlue
      default: return '#A9CFF4';         // Another light blue shade
    }
  }
  
  
  async function drawGroupedBarChart() {
    const { data } = await fetchPaymentData();
    const { months, datasets } = processGroupedData(data);
  
    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: months,
        datasets: datasets
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
          title: { display: true, text: 'Payments by Month and Method' }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Month'
            },
            stacked: false
          },
          y: {
            title: {
              display: true,
              text: 'Number of Payments'
            },
            min: 0,          // optional: start from zero
            max: 10,        // <-- set your upper limit here
            ticks: {
            stepSize: 1   // optional: control tick intervals
            }

          }
        }
      }
    });
  }
  
  drawGroupedBarChart();
    
}

drawCharts();

// DOM elements
const chatBoxModal = document.getElementById('chatbox-modal');
const chatContent = document.querySelector('.chat-content');
const chatMessageInput = document.getElementById('chat-message');
const sendMessageButton = document.getElementById('send-message');

// API key
const API_KEY = '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';

// Abort controller
let controller = null;
let isThinking = false;

// Send message function
async function sendMessageToAI(userMessage) {
    appendMessage('user', userMessage);
    showLoading();
  
    controller = new AbortController();
    const signal = controller.signal;
  
    try {
      isThinking = true;
      toggleButton();
  
      // Get latest database data
      const dbData = await fetchPaymentData();
  
      // Combine the user message with relevant data
      const prompt = `
  You are a helpful assistant. Here's the latest payment data from the database:
  ${JSON.stringify(dbData)}
  
  User question: ${userMessage}
  Provide a helpful response based on the data.
      `;
  
      const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
        method: "POST",
        signal: signal,
        headers: {
          "Authorization": `Bearer ${API_KEY}`,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          model: "google/gemini-2.0-flash-001",
          messages: [{ role: "user", content: prompt }]
        })
      });
  
      const data = await response.json();
      removeLoading();
  
      const aiReply = data?.choices?.[0]?.message?.content;
      appendMessage('ai', aiReply || "I couldn't understand that.");
  
    } catch (error) {
      removeLoading();
      if (signal.aborted) {
        appendMessage('ai', "Request cancelled.");
      } else {
        appendMessage('ai', "An error occurred.");
      }
    } finally {
      isThinking = false;
      toggleButton();
      controller = null;
    }
  }
  

// Append messages to chat window
function appendMessage(sender, message) {
  const messageElement = document.createElement('div');
  messageElement.classList.add(sender === 'user' ? 'user-message' : 'ai-message');
  messageElement.textContent = message;
  chatContent.appendChild(messageElement);
  chatContent.scrollTop = chatContent.scrollHeight;
}

// Loading animation
function showLoading() {
  const loading = document.createElement('div');
  loading.classList.add('ai-message');
  loading.id = 'loading';
  loading.textContent = 'Thinking...';
  chatContent.appendChild(loading);
  chatContent.scrollTop = chatContent.scrollHeight;
}

function removeLoading() {
  const loading = document.getElementById('loading');
  if (loading) {
    chatContent.removeChild(loading);
  }
}

// Toggle send/stop button
function toggleButton() {
  if (isThinking) {
    sendMessageButton.innerHTML = `<i class="fa-solid fa-stop"></i>`;
  } else {
    sendMessageButton.innerHTML = `<i class="fa-solid fa-paper-plane"></i>`;
  }
}

// Send/Stop button click handler
sendMessageButton.addEventListener('click', () => {
  const userMessage = chatMessageInput.value.trim();

  if (isThinking && controller) {
    controller.abort(); // Cancel current fetch
  } else if (userMessage !== '') {
    sendMessageToAI(userMessage);
    chatMessageInput.value = '';
  }
});

// Optional: Send message on Enter key press
chatMessageInput.addEventListener('keydown', (event) => {
  if (event.key === 'Enter') {
    sendMessageButton.click();
  }
});

