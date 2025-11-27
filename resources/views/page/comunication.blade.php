<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Speaking Assistant (Full Version)</title>
    <!-- Ensure this line is present for Laravel CSRF token to work -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e0f2f7; /* Light blue background */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1.5rem; /* More rounded corners */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); /* Stronger, softer shadow */
            width: 100%;
            max-width: 900px; /* Max width for the main container */
            margin: 0 auto;
            text-align: center;
        }
        h1 {
            font-size: 2.5rem; /* Larger title */
            font-weight: 700; /* Bolder */
            margin-bottom: 2rem;
            color: #2c3e50; /* Darker, professional blue */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        h1 svg {
            width: 2.5rem;
            height: 2.5rem;
            color: #3498db; /* Blue icon */
        }
        .control-group {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            justify-content: center;
            gap: 1.5rem; /* Space between controls */
            margin-bottom: 2rem;
        }
        .control-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .control-item label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #555;
        }
        .control-item select {
            padding: 0.6rem 1rem;
            border: 1px solid #ccc;
            border-radius: 0.75rem; /* Rounded dropdown */
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
            background-color: #f8f8f8;
            min-width: 180px;
            font-size: 1rem;
            color: #333;
            transition: all 0.2s ease-in-out;
            -webkit-appearance: none; /* Remove default arrow */
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%20viewBox%3D%220%200%20292.4%20292.4%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M287%20197.3L159.9%2069.2c-3.7-3.7-9.7-3.7-13.4%200L5.4%20197.3c-3.7%203.7-3.7%209.7%200%2013.4s9.7%203.7%2013.4%200l135.2-135.2L273.6%20210.7c3.7%203.7%209.7%203.7%2013.4%200s3.7-9.7%200-13.4z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 0.7em top 50%, 0 0;
            background-size: 0.65em auto, 100%;
        }
        .control-item select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
            outline: none;
        }

        button {
            background-color: #3498db; /* Blue button */
            color: #ffffff;
            font-weight: 600;
            padding: 0.8rem 2.5rem; /* More padding */
            border-radius: 2rem; /* Pill shape */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15); /* Softer, larger shadow */
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex; /* Use inline-flex for button content alignment */
            align-items: center;
            gap: 0.75rem;
        }
        button:hover {
            background-color: #2980b9; /* Darker blue on hover */
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px); /* Lift effect */
        }
        button:active {
            transform: translateY(0); /* Press effect */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        button svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        #loading {
            color: #777;
            margin-top: 1.5rem;
            font-size: 1rem;
            display: none;
            text-align: center;
        }

        /* New Flex Container for Response and Character */
        .main-conversation-area {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on small screens */
            gap: 2rem; /* Space between response and character */
            margin-top: 2.5rem;
            align-items: flex-start; /* Align top */
        }

        .left-panel { /* Contains response-section */
            flex: 1; /* Take up available space */
            min-width: 350px; /* Minimum width before wrapping */
            /* No padding or background here, it's handled by response-section */
        }

        .response-section {
            padding: 2rem;
            background-color: #f0f8ff; /* Lighter blue background */
            border-radius: 1rem;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: left;
            border: 1px solid #d0e8f2;
            width: 100%; /* Fill its parent .left-panel */
        }
        .response-section p {
            font-size: 1.15rem; /* Slightly larger text */
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }
        .response-section strong {
            color: #2c3e50;
            font-weight: 700;
        }
        #userSpeech {
            color: #555;
            font-style: italic;
        }
        /* Removed .ai-response-container and its styles for the small avatar here */
        /* as the main character will be on the right panel */
        #aiEnglishResponse {
            color: #222;
            font-weight: 600;
        }
        #aiVietnameseResponse {
            font-size: 0.95rem;
            color: #777;
            font-style: normal; /* No italic for translation */
            margin-top: 0.5rem;
            border-top: 1px dashed #eee; /* Separator */
            padding-top: 0.5rem;
        }

        /* Right Panel for Character Display */
        .right-panel {
            flex: 1; /* Take up available space */
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 250px; /* Minimum width for character area */
            background-color: #f8f8f8; /* Light background for character area */
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem; /* More padding for the character */
        }

        .main-ai-character-image {
            max-width: 100%;
            height: auto;
            border-radius: 1rem; /* Rounded corners for the image */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        /* History Section */
        .history-section {
            margin-top: 2.5rem;
            padding: 2rem;
            background-color: #fdfdfd; /* White background */
            border-radius: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: left;
            border: 1px solid #eee;
            width: 100%; /* Ensure it takes full width of container */
        }
        .history-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #34495e; /* Darker blue-grey */
            text-align: center;
        }
        #chatHistory {
            border: 1px solid #e0e0e0;
            padding: 1rem;
            border-radius: 0.75rem;
            max-height: 18rem; /* Increased history height */
            overflow-y: auto;
            background-color: #ffffff;
            line-height: 1.5;
            box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.05);
        }
        #chatHistory p {
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px dotted #f0f0f0; /* Dotted separator for history entries */
        }
        #chatHistory p:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        #chatHistory strong {
            color: #444;
        }
        .ai-vietnamese-translation {
            font-size: 0.85rem;
            color: #888;
            font-style: italic;
            display: block; /* Ensure it's on a new line */
            margin-top: 0.2rem;
        }
        /* Styles for history avatar */
        .history-entry {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px dotted #f0f0f0;
        }
        .history-entry:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .history-avatar {
            width: 35px; /* Smaller avatar for history */
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .history-content {
            flex-grow: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-conversation-area {
                flex-direction: column;
            }
            .left-panel, .right-panel {
                flex: none;
                width: 100%;
                min-width: unset;
            }
            .right-panel { /* Character panel */
                order: -1; /* Move character to top on small screens */
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            AI Speaking Assistant
        </h1>

        <div class="control-group">
            <div class="control-item">
                <label for="topic">Topic:</label>
                <select id="topic">
                    <option value="general">General Conversation</option>
                    <option value="travel">Travel</option>
                    <option value="shopping">Shopping</option>
                    <option value="food">Food</option>
                    <option value="technology">Technology</option>
                    <!-- Add more topics as needed -->
                </select>
            </div>
            <div class="control-item">
                <label for="difficulty">Level:</label>
                <select id="difficulty">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
        </div>

        <button onclick="startListening()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75V5.25"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a.75.75 0 00-.75.75v12a.75.75 0 001.5 0V5.25A.75.75 0 0012 4.5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12h-7.5"></path>
            </svg>
            Start Talking
        </button>
        <p id="loading">AI is thinking...</p>

        <div class="main-conversation-area">
            <div class="left-panel">
                <div class="response-section">
                    <p><strong>You said:</strong></p>
                    <p id="userSpeech" class="text-gray-700">...</p>

                    <p class="mt-4"><strong>AI says:</strong></p>
                    <p id="aiEnglishResponse" class="text-gray-900 font-medium">...</p>
                    <p id="aiVietnameseResponse" class="ai-vietnamese-translation">...</p>
                </div>
            </div>
            <div class="right-panel">
                <img id="mainAiCharacter" src="{{ asset('images/anh1.jpg') }}" alt="AI Character" class="main-ai-character-image">
            </div>
        </div>

        <div class="history-section">
            <h3 class="text-xl font-semibold mb-3 text-indigo-600">Conversation History:</h3>
            <div id="chatHistory">
                <!-- History will appear here -->
            </div>
        </div>
    </div>

    <script>
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const loadingSpinner = document.getElementById("loading");
        const chatHistoryElement = document.getElementById("chatHistory");
        const topicSelect = document.getElementById("topic");
        const difficultySelect = document.getElementById("difficulty");
        const aiEnglishResponseElement = document.getElementById("aiEnglishResponse");
        const aiVietnameseResponseElement = document.getElementById("aiVietnameseResponse");
        const mainAiCharacter = document.getElementById("mainAiCharacter"); // Get the main character image element

        // Store conversation history in session (temporary)
        let conversationHistory = [];
        let isListening = false; // Flag to check listening state

        // URLs for AI and User avatars (small ones for history)
        const userAvatarUrl = "{{ asset('images/anh1.jpg') }}";
        // URLs for the main AI character image (default and speaking)
        const aiDefaultImageUrl = "{{ asset('images/anh1.jpg') }}"; // Default AI image
        const aiSpeakingImageUrl = "{{ asset('images/anh1.jpg') }}"; // AI image when speaking

        if (!SpeechRecognition) {
            const messageBox = document.createElement('div');
            messageBox.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: 1000;
                text-align: center;
            `;
            messageBox.innerHTML = `
                <p>Your browser doesn't support speech recognition. Please use Chrome or a compatible browser.</p>
                <button onclick="this.parentNode.remove()" style="margin-top: 15px; padding: 8px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">OK</button>
            `;
            document.body.appendChild(messageBox);
        }

        const recognition = new SpeechRecognition();
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        recognition.onstart = () => {
            isListening = true;
            document.getElementById("userSpeech").innerText = "Listening...";
            console.log('Speech recognition started.');
        };

        recognition.onend = () => {
            isListening = false;
            console.log('Speech recognition ended.');
        };

        function startListening() {
            if (isListening) {
                console.warn("Speech recognition is already active.");
                return;
            }

            aiEnglishResponseElement.innerText = "...";
            aiVietnameseResponseElement.innerText = "...";
            loadingSpinner.style.display = 'none';

            recognition.start();

            recognition.onresult = async (event) => {
                const userText = event.results[0][0].transcript;
                document.getElementById("userSpeech").innerText = userText;

                loadingSpinner.style.display = 'block';

                const selectedTopic = topicSelect.value;
                const selectedDifficulty = difficultySelect.value;

                conversationHistory.push({ role: 'user', content: userText });
                updateChatHistory();

                try {
                    const response = await fetch('/api/chat-with-ai', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            message: userText,
                            topic: selectedTopic,
                            difficulty: selectedDifficulty,
                            history: conversationHistory
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        console.error("Backend error response:", errorData);
                        throw new Error(`HTTP error! status: ${response.status} - ${errorData.error || 'Unknown error'}`);
                    }

                    const data = await response.json();
                    console.log("Received data from Laravel backend:", data);

                    const aiEnglishText = data.englishResponse;
                    const aiVietnameseText = data.vietnameseResponse;

                    aiEnglishResponseElement.innerText = aiEnglishText || "No English response.";
                    aiVietnameseResponseElement.innerText = aiVietnameseText || "No Vietnamese translation.";

                    const historyContent = (aiEnglishText || "") + "\nVietnamese: " + (aiVietnameseText || "");
                    conversationHistory.push({ role: 'ai', content: historyContent });
                    updateChatHistory();

                    const utterance = new SpeechSynthesisUtterance(aiEnglishText);
                    utterance.lang = 'en-US';

                    // --- Lip-sync logic ---
                    utterance.onstart = () => {
                        mainAiCharacter.src = aiSpeakingImageUrl; // Switch to speaking image
                    };

                    utterance.onend = () => {
                        mainAiCharacter.src = aiDefaultImageUrl; // Switch back to default image
                    };

                    utterance.onerror = (event) => {
                        console.error('Speech synthesis error:', event);
                        mainAiCharacter.src = aiDefaultImageUrl; // Revert on error
                    };
                    // --- End Lip-sync logic ---

                    window.speechSynthesis.speak(utterance);

                } catch (error) {
                    console.error("Error getting AI response:", error);
                    aiEnglishResponseElement.innerText = "Error: Could not get a response from AI.";
                    aiVietnameseResponseElement.innerText = error.message || "";
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            };

            recognition.onerror = (event) => {
                console.error("Speech Recognition Error:", event.error);
                document.getElementById("userSpeech").innerText = "Error: " + event.error + ". Please try again.";
                loadingSpinner.style.display = 'none';
            };
        }

        function updateChatHistory() {
            chatHistoryElement.innerHTML = '';
            conversationHistory.forEach(entry => {
                const historyEntryDiv = document.createElement('div');
                historyEntryDiv.classList.add('history-entry');

                const avatarImg = document.createElement('img');
                avatarImg.classList.add('history-avatar');

                const contentDiv = document.createElement('div');
                contentDiv.classList.add('history-content');

                const p = document.createElement('p');

                if (entry.role === 'user') {
                    avatarImg.src = userAvatarUrl;
                    avatarImg.alt = "User Avatar";
                    p.innerHTML = `<strong>You:</strong> ${entry.content}`;
                } else {
                    // Note: The main character image handles the "speaking" animation.
                    // This small avatar in history remains static.
                    avatarImg.src = aiDefaultImageUrl; // Use default AI image for history avatar
                    avatarImg.alt = "AI Avatar";
                    const parts = entry.content.split('\nVietnamese: ');
                    const englishPart = parts[0];
                    const vietnamesePart = parts[1] || '';
                    p.innerHTML = `<strong>AI:</strong> ${englishPart}<br><span class="ai-vietnamese-translation">${vietnamesePart}</span>`;
                }
                
                historyEntryDiv.appendChild(avatarImg);
                contentDiv.appendChild(p);
                historyEntryDiv.appendChild(contentDiv);
                chatHistoryElement.appendChild(historyEntryDiv);
            });
            chatHistoryElement.scrollTop = chatHistoryElement.scrollHeight;
        }
    </script>

</body>
</html>
