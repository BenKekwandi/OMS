
export const rules = {
  validate: (value) => {
    return Object.values(rules).every(rule => rule(value));
  },
  required: (value) => value !== undefined && value !== null && value !== '' || "Field is required",
  email: (value) => {
    if (value != '') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(value) || "Invalid email format";
    }
  },
  phone: (value) => {
    if (value != '') {
      const phoneRegex = /^\+?[0-9]+$/;
      return phoneRegex.test(value) || "Phone number must contain only digits";
    }
  },
  invoice_amount: (value, net_price) => {
    return value <= net_price || `Amount can't exceed Sell price: ${net_price}`
  },
  order_deadline: (deadline) => {
    const currentDate = new Date();
    currentDate.setTime(currentDate.getTime() + 3 * (24 * 60 * 60 * 1000));
    currentDate.setHours(0, 0, 0, 0);

    const checkedDate = new Date(deadline);
    checkedDate.setHours(0, 0, 0, 0);


    return (checkedDate.getTime() >= currentDate.getTime()) || 'Deadline needs to be at least 3 days ahead of today.';
  },
  delivery_days: (value, payment_deadline) => {
    let currentDate = new Date();
    let deadlineDate = new Date(payment_deadline);
    currentDate.setTime(currentDate.getTime() + value * (24 * 60 * 60 * 1000));
    deadlineDate.setTime(deadlineDate.getTime() - 3 * (24 * 60 * 60 * 1000));

    currentDate.setHours(0, 0, 0, 0);
    deadlineDate.setHours(0, 0, 0, 0);

    return currentDate.getTime() <= deadlineDate.getTime() || `Can't exceed 3 days before deadline: ${payment_deadline}`
  },
  left_to_pay: (leftToPay, newPayments) => {

    const amountCheck = newPayments.reduce((acc, curr) => acc + curr.amount, 0)

    return amountCheck <= leftToPay || `All payments can't exceed ${leftToPay} (Left to pay amount)`
  },
  discount: (value) => {
    return value <= 0 || `Amount can't be negative`
  }
};