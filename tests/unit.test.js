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

test("Check AddColors", async () => {
  const response = await post("AddColor", { color: "blue", userId: 1 });
  expect(response).toEqual({ error: "" });
});

test("Check SearchColors Success", async () => {
  const response = await post("SearchColors", { search: "yellow", userId: 1 });
  expect(response.error).toBe("");
  expect(response.results).toContain("yellow");
});

test("Check SearchColors Failure", async () => {
  const response = await post("SearchColors", { search: "tan", userId: 1 });
  expect(response.error).toBe("No Records Found");
});
