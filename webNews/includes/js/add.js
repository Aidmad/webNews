let optionsButton = document.querySelectorAll(".option-button");
let advancedOptionButton = document.querySelectorAll(".adv-option-button");
let fontName = document.getElementById("fontName");
let fontSizeRef = document.getElementById("fontSize");
let writingArea = document.getElementById("text-input");
let linkButton = document.getElementById("createLink");
let alignButtons = document.querySelectorAll(".align");
let spacingButtons = document.querySelectorAll(".spacing");
let formatButtons = document.querySelectorAll(".format");
let scriptButtons = document.querySelectorAll(".script");

// LIST OF FONTS
let fontList = [
    "Arial",
    "Verdana",
    "Times New Roman",
    "Garamond",
    "Georgia",
    "Courier New",
    "cursive",
];

// INTIAL SETTIGNS
const initializer = () => {
    // CALLS HIGHLIGHTED BUTTONS
    highlighter(alignButtons, true);
    highlighter(spacingButtons, true);
    highlighter(formatButtons, false);
    highlighter(scriptButtons, true);

    // CREATE OPTIONS FOR FONT NAMES
    fontList.map ((value) => {
        let option = document.createElement("option");
        option.value = value;
        option.innerHTML = value;
        fontName.appendChild(option);
    });

    // fontSize ALLOWS ONLY TILL 7
    for (let i = 1; i <= 7; i ++) {
        let option = document.createElement("option");
        option.value = i;
        option.innerHTML = i;
        fontSizeRef.appendChild(option);
    }

    // DEFAULT SIZE
    fontSizeRef.value = 3;
};

// MAIN LOGIC
const modifyText = (command, defaultUi, value) => {
    // EXECUTES COMMAND ON SELECTED TEXT
    document.execCommand(command, defaultUi, value);
};

// FOR BASIC OPERATIONS THT DONT NEED VALUE PARAMETER
optionsButton.forEach((button) => {
    button.addEventListener("click", () => {
        modifyText(button.id, false, null);
    });
});

// OPTIONS THT NEED VALUE PARAMETER
advancedOptionButton.forEach((button) => {
    button.addEventListener("change", () => {
        modifyText(button.id, false, button.value);
    });
});

// LINK
linkButton.addEventListener("click", () => {
    let userLink = prompt("Enter a URL");
    // IF LINK HAS HTTP THEM ASS DIRECTLY ELSE ADD HTTPS
    if (/http/i.test(userLink)) {
        modifyText(linkButton.id, false, userLink);
    } else {
        userLink = "http://" + userLink;
        modifyText(linkButton.id, false, userLink);
    }
});

// HIGHLIGHT CLICKED BUTTONS
const highlighter = (className, needsRemoval) => {
    className.forEach((button) => {
        button.addEventListener("click", () => {
            // ONLY ONE BUTTON SHOULD BE HIGHLIGHTED WHEN needsRemoval = true
            if (needsRemoval) {
                let alreadyActive = false;

                // IF CURRENTLY CLICKED BUTTON IS ALREADY ACTIVE
                if (button.classList.contains("active")) {
                    alreadyActive = true;
                }

                // REMOVE HIGHLIGHT FROM OTHER BUTTONS
                highlighterRemover(className);
                if (!alreadyActive) {
                    //HIGHLIGHT CLICKED BUTTON
                    button.classList.add("active");
                }
            } else {
                // IF OTHERS BUTTONS CAN BE HIGHLIGHTED
                button.classList.toggle("active");
            }
        });
    });
};

const highlighterRemover = (className) => {
    className.forEach((button) => {
        button.classList.remove("active");
    });
}

// Set font color function
const setFontColor = (color) => {
    modifyText('foreColor', false, color);
};

// Add an event listener to the color input
const colorInput = document.getElementById('foreColor');
colorInput.addEventListener('input', (event) => {
    setFontColor(event.target.value);
});

window.onload = initializer();