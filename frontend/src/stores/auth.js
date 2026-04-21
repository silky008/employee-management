import { ref } from "vue";
import api from "@/services/api";

const user = ref(null);

export const useAuth = () => {
  const fetchUser = async () => {
    if (!user.value) {
      const res = await api.get("/me");
      user.value = res.data;
    }
  };

  return { user, fetchUser };
};
