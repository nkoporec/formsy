module.exports = {
    theme: {
        extend: {
            colors: {
                formsydark: "#0f2137",
                formsypurple: "#5f6fd7",
                formsypurplealt: "#747bd4",
            },
            zIndex: {
                "0": 0,
                "10": 10,
                "20": 20,
                "30": 30,
                "40": 40,
                "50": 50,
                "25": 25,
                "50": 50,
                "75": 75,
                "100": 100,
                "99999": 99999,
                auto: "auto",
            },
        },
    },
    variants: {
        width: ["responsive", "hover", "focus"],
        textColor: ["responsive", "hover", "focus", "group-hover"],
    },
    purge: [],
    plugins: [],
};
