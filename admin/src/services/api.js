import axios from "axios";

const api = axios.create({
    baseURL: "https://manager-life.herokuapp.com/api/",
});

export default api;
