import { Form as VeeForm, Field as VeeField, defineRule, ErrorMessage, configure } from "vee-validate";
import { numeric, required, min, max, alpha_spaces as alphaSpaces, email, min_value as minVal, confirmed as maxVal, confirmed, not_one_of as excluded } from "@vee-validate/rules";

export default {
    install(app) {
        app.component("VeeForm", VeeForm);
        app.component("VeeField", VeeField);
        app.component("ErrorMessage", ErrorMessage)

        defineRule('numeric', numeric)
        defineRule('required', required);
        defineRule('min', min);
        defineRule('max', max);
        defineRule('alpha_spaces', alphaSpaces);
        defineRule('email', email);
        defineRule('min_value', minVal);
        defineRule('max_value', maxVal);
        defineRule('password_mismatch', confirmed);
        defineRule("excluded", excluded);
        defineRule("country_excluded", excluded);

        configure({
            generateMessage: (ctx) => {
                const messages = {
                    numeric: `The field ${ctx.field} must be numeric.`,
                    required: `The field ${ctx.field} is required.`,
                    min: `Password must be at least 8 characters`, //Must be used in password field
                    max: `The field ${ctx.field} is too long.`,
                    alpha_spaces: `The field ${ctx.field} may only contain alphabetical characters and spaces.`,
                    email: `The field ${ctx.field} must be a valid email.`,
                    min_value: `The field ${ctx.field} is too low.`,
                    max_value: `The field ${ctx.field} is too high.`,
                    excluded: `You are not allowed to use this value for the field ${ctx.field}.`,
                    password_mismatch: "The passwords don't match.",

                };
                const message = messages[ctx.rule.name]
                    ? messages[ctx.rule.name]
                    : `The field ${ctx.field} is invalid.`;

                return message;
            },
        });
    },
}