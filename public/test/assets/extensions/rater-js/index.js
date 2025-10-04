/*! rater-js. [c] 2018 Fredrik Olsson. MIT License */
let css = document.createElement("style");
css.textContent = `
    .star-rating {
        width: fit-content;
        display: flex;
        flex-direction: row-reverse;
    }
    .star-rating > input {
        display: none;
    }
    .star-rating > label {
        cursor: pointer;
        width: 40px;
        height: 40px;
        background-image: url("data:image/svg+xml;charset=UTF-8,${encodeURIComponent(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>'
        )}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 76%;
        transition: .3s;
    }
    .star-rating > input:checked ~ label,
    .star-rating > input:checked ~ label ~ label {
        filter: invert(83%) sepia(32%) saturate(1115%) hue-rotate(358deg) brightness(100%) contrast(96%);
    }
    .star-rating > input:not(:checked) ~ label:hover,
    .star-rating > input:not(:checked) ~ label:hover ~ label {
        filter: invert(83%) sepia(32%) saturate(1115%) hue-rotate(358deg) brightness(100%) contrast(96%) opacity(0.5);
    }
`;
document.head.appendChild(css);

function raterJs(options) {
    const stars = Array.from({ length: 5 }, (_, i) => {
        const radio = document.createElement("input");
        radio.type = "radio";
        radio.id = `star${5 - i}`;
        radio.value = 5 - i;
        radio.name = "rating";

        const label = document.createElement("label");
        label.htmlFor = `star${5 - i}`;
        label.title = `${5 - i} stars`;

        return { radio, label };
    });

    const ratingDiv = document.createElement("div");
    ratingDiv.className = "star-rating";
    stars.forEach(({ radio, label }) => {
        ratingDiv.appendChild(radio);
        ratingDiv.appendChild(label);
    });

    options.element.appendChild(ratingDiv);

    const setRating = (rating) => {
        stars.forEach(({ radio }) => {
            radio.checked = parseInt(radio.value) === rating;
        });
    };

    stars.forEach(({ radio }) => {
        radio.addEventListener("change", () => {
            if (typeof options.rateCallback === "function") {
                options.rateCallback(parseInt(radio.value), () => {});
            }
        });
    });

    return {
        setRating,
        getRating: () => {
            const checked = stars.find(({ radio }) => radio.checked);
            return checked ? parseInt(checked.radio.value) : 0;
        },
    };
}
