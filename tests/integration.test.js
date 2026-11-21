const { test, expect } = require("@jest/globals");

async function post(endpoint, data) {
  const response = await fetch(`http://127.0.0.1:8000/API/${endpoint}.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });

  expect(response.ok).toBe(true);
  return response.json();
}

test("Login success", async () => {
  const response = await post("Login", {
    login: "testing",
    password: "testing",
  });

  expect(response).toEqual({
    id: 1,
    firstName: "testing",
    lastName: "testing",
    error: "",
  });
});

test("Login failure", async () => {
  const response = await post("Login", {
    login: "testing",
    password: "not testing",
  });

  expect(response.id).toBe(0);
  expect(response.error).toBe("No Records Found");
});

test("SearchColors works as intended", async () => {
  expect(await post("AddColor", { color: "green", userId: 1 })).toEqual({
    error: "",
  });

  const found = await post("SearchColors", { search: "green", userId: 1 });
  expect(found.error).toBe("");
  expect(found.results).toContain("green");

  const otherUser = await post("SearchColors", { search: "green", userId: 2 });
  expect(otherUser.error).toBe("No Records Found");
});
